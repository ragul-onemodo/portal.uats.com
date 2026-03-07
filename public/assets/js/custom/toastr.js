// Global Toastr configuration (put in your main JS file or app layout)
toastr.options = {
    "closeButton": true,              // Show × button
    "debug": false,
    "newestOnTop": true,              // New messages appear on top
    "progressBar": true,              // Show progress bar when timeout
    "positionClass": "toast-top-right", // Common positions: 
    // toast-top-right, toast-top-left, 
    // toast-bottom-right, toast-bottom-left,
    // toast-top-full-width, toast-bottom-full-width
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",                // Auto hide after 5 seconds
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};