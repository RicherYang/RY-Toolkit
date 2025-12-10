import $ from 'jquery';

$(function () {
    const $progress = $('#export-progress .ry-progress-bar');

    const doExport = function (postData) {
        $.ajax({
            url: RyToolkitToolsParams.exportUrl,
            method: 'POST',
            dataType: 'json',
            data: postData
        }).done(function (Jdata) {
            if (Jdata.success) {
                if (Jdata.data.continue) {
                    $progress.width(Jdata.data.progress + '%');
                    RyToolkitToolsParams.exportUrl = Jdata.data.url;
                    doExport('');
                } else {
                    $progress.width('100%');
                    fetch(Jdata.data.url)
                        .then(resp => resp.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = 'export-db.zip';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            $('#export-progress').hide();
                        });
                }
            }
        });
    };

    $('#ry-export-db').on('click', function () {
        $progress.width('0%');
        $('#export-progress').show();
        doExport($progress.closest('fieldset').serialize());
    });
});
