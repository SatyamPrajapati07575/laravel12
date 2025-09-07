{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert2 Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        customClass: {
            popup: 'colored-toast'
        }
    });

    // Enhanced Toast Configuration for different types
    const showToast = (type, title, text = '') => {
        const config = {
            icon: type,
            title: title,
            text: text,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: type === 'error' ? 6000 : 4000, // Error messages show longer
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        };

        // Customize based on type
        switch(type) {
            case 'success':
                config.background = '#d4edda';
                config.color = '#155724';
                break;
            case 'error':
                config.background = '#f8d7da';
                config.color = '#721c24';
                break;
            case 'warning':
                config.background = '#fff3cd';
                config.color = '#856404';
                break;
            case 'info':
                config.background = '#d1ecf1';
                config.color = '#0c5460';
                break;
        }

        Toast.fire(config);
    };

    // Laravel Session Messages Auto-Display
    @if(session('success'))
        showToast('success', 'Success!', "{{ session('success') }}");
    @endif

    @if(session('error'))
        showToast('error', 'Error!', "{{ session('error') }}");
    @endif

    @if(session('warning'))
        showToast('warning', 'Warning!', "{{ session('warning') }}");
    @endif

    @if(session('info'))
        showToast('info', 'Info!', "{{ session('info') }}");
    @endif

    {{-- Display validation errors as individual toasts --}}
    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast('error', 'Validation Error', "{{ $error }}");
        @endforeach
    @endif

    {{-- Custom CSS for better toast appearance --}}
    const style = document.createElement('style');
    style.textContent = `
        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }
        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }
        .colored-toast.swal2-icon-warning {
            background-color: #f8bb86 !important;
        }
        .colored-toast.swal2-icon-info {
            background-color: #87adbd !important;
        }
        .colored-toast.swal2-icon-question {
            background-color: #87ceeb !important;
        }
        .colored-toast .swal2-title {
            color: white !important;
            font-size: 16px !important;
        }
        .colored-toast .swal2-content {
            color: white !important;
            font-size: 14px !important;
        }
        .colored-toast .swal2-close {
            color: white !important;
        }
        .swal2-timer-progress-bar {
            background: rgba(255, 255, 255, 0.6) !important;
        }
    `;
    document.head.appendChild(style);

    // Additional helper functions
    window.showSuccessToast = (title, text = '') => showToast('success', title, text);
    window.showErrorToast = (title, text = '') => showToast('error', title, text);
    window.showWarningToast = (title, text = '') => showToast('warning', title, text);
    window.showInfoToast = (title, text = '') => showToast('info', title, text);

    // Confirmation dialog helper
    window.showConfirmDialog = (title, text, confirmButtonText = 'Yes', cancelButtonText = 'No') => {
        return Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            reverseButtons: true
        });
    };

    // // Loading dialog helper
    // window.showLoading = (title = 'Loading...', text = 'Please wait') => {
    //     Swal.fire({
    //         title: title,
    //         text: text,
    //         allowOutsideClick: false,
    //         allowEscapeKey: false,
    //         showConfirmButton: false,
    //         didOpen: () => {
    //             Swal.showLoading();
    //         }
    //     });
    // };

    window.hideLoading = () => {
        Swal.close();
    };

    // // Input dialog helper
    // window.showInputDialog = (title, inputPlaceholder = '', inputType = 'text') => {
    //     return Swal.fire({
    //         title: title,
    //         input: inputType,
    //         inputPlaceholder: inputPlaceholder,
    //         showCancelButton: true,
    //         inputValidator: (value) => {
    //             if (!value) {
    //                 return 'This field is required!'
    //             }
    //         }
    //     });
    // };
</script>