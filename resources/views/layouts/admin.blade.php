<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
  .modal-advanced {
    border-radius: 0.5rem;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }
  .modal-advanced .modal-header {
    border-bottom: none;
    padding: 1.5rem;
    position: relative;
  }
  .modal-advanced .modal-title {
    font-weight: 600;
    color: #2d3748;
  }
  .modal-advanced .modal-body {
    padding: 0 1.5rem 1.5rem;
  }
  .modal-advanced .modal-footer {
    border-top: none;
    padding: 0 1.5rem 1.5rem;
    justify-content: flex-end;
  }
  .modal-advanced .close {
    position: absolute;
    right: 1.5rem;
    top: 1.5rem;
    font-size: 1.5rem;
    opacity: 0.5;
  }
  .form-advanced .form-group {
    margin-bottom: 1.25rem;
  }
  .form-advanced .form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
  }
  .form-advanced .form-control, 
  .form-advanced .form-select {
    border: 1px solid #e2e8f0;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    height: calc(2.25rem + 2px);
  }
  .form-advanced .form-control:focus, 
  .form-advanced .form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
  }
  .image-preview-container {
    margin-top: 1rem;
  }
  .image-preview {
    max-width: 100%;
    max-height: 200px;
    border-radius: 0.375rem;
    border: 1px dashed #cbd5e0;
    padding: 0.5rem;
    display: none;
  }
  .color-preview {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 8px;
    border: 1px solid #e2e8f0;
  }
</style>
    
    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            @include('admin.partials.navbar')
            
            <!-- Main Content -->
            <div class="container-fluid px-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/admin.js') }}"></script>
    
    @stack('scripts')
</body>
</html>