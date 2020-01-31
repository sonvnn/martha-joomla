jQuery(function($){
    /*
     * For the sake keeping the code clean and the examples simple this file
     * contains only the plugin configuration & callbacks.
     *
     * UI functions ui_* can be located in: style_ui.js
     */
    ImageGallery.initUploader();
    $('#image_gallery_uploader').dmUploader({ //
        url: ImageGallery.ajaxUrl,
        maxFileSize: ImageGallery.maxFileSize*1000000, // 3 Megs
        allowedTypes: 'image/*',
        extFilter: ImageGallery.extFilter,
        onDragEnter: function(){
            // Happens when dragging something over the DnD area
            this.addClass('active');
        },
        onDragLeave: function(){
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active');
        },
        onInit: function(){
            // Plugin is ready to use
            ImageGallery.ui_add_log('Penguin initialized :)', 'info');
        },
        onComplete: function(){
            // All files in the queue are processed (success or error)
            ImageGallery.ui_add_log('All pending tranfers finished');
        },
        onNewFile: function(id, file){
            // When a new file is added using the file selector or the DnD area
            ImageGallery.ui_add_log('New file added #' + id);
            ImageGallery.ui_multi_add_file(id, file);
        },
        onBeforeUpload: function(id){
            // about tho start uploading a file
            ImageGallery.ui_add_log('Starting the upload of #' + id);
            ImageGallery.ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            ImageGallery.ui_multi_update_file_progress(id, 0, '', true);
        },
        onUploadCanceled: function(id) {
            // Happens when a file is directly canceled by the user.
            ImageGallery.ui_multi_update_file_status(id, 'warning', 'Canceled by User');
            ImageGallery.ui_multi_update_file_progress(id, 0, 'warning', false);
        },
        onUploadProgress: function(id, percent){
            // Updating file progress
            ImageGallery.ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            ImageGallery.ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
            ImageGallery.ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
            ImageGallery.ui_multi_update_file_data(id, data);
            ImageGallery.ui_multi_update_file_status(id, 'success', 'Upload Complete');
            ImageGallery.ui_multi_update_file_progress(id, 100, 'success', false);
        },
        onUploadError: function(id, xhr, status, message){
            ImageGallery.ui_multi_update_file_status(id, 'danger', message);
            ImageGallery.ui_multi_update_file_progress(id, 0, 'danger', false);
        },
        onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            ImageGallery.ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
        },
        onFileSizeError: function(file){
            ImageGallery.ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
        }
        ,
        onFileExtError: function(file){
            ImageGallery.ui_add_log('File \'' + file.name + '\' cannot be added: extension invalid', 'danger');
        }
    });
});