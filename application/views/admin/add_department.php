<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Department Setup
            <small>Setting up Department</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Departments</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Employee List</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="departmentAddForm" action="<?= site_url('Main/department_add'); ?>" method="POST">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Department Name</label>
                                <input type="text" name="dept_desc" id="dept_desc" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Manager ID</label>
                                <input type="text" name="manager_id" id="manager_id" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Support ID</label>
                                <input type="text" name="sup_id" id="sup_id" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-info float-left">Submit</button>
                    <a href="<?= base_url(); ?>sys/admin/team" class="btn btn-danger float-left">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
	/* Popup: Overall container */
.swal-custom-popup {
    padding: 20px; /* Adjust padding */
}

/* Title: Customize font size and weight */
.swal-custom-title {
    font-size: 3em; /* Larger title */
    font-weight: bold; /* Make the title bold */
}

/* Content: Customize font size and line spacing */
.swal-custom-content {
    font-size: 5.5em; /* Larger content text */
    line-height: 1.6; /* Adjust line spacing */
}

/* Confirm Button: Customize size and padding */
.swal-custom-confirm-btn {
    font-size: 1.6em; /* Larger button text */
    padding: 10px 20px; /* Adjust padding for the button */
}

/* Optional: Increase button border radius and background color */
.swal-custom-confirm-btn, .swal-custom-cancel-btn {
    border-radius: 8px; /* Make the buttons more rounded */
    background-color: #007bff; /* Change button color */
    color: #fff; /* Ensure text is white */
}

.swal-custom-confirm-btn:hover, .swal-custom-cancel-btn:hover {
    background-color: #0056b3; /* Darker color on hover */
}

.swal-custom-text {
    font-size: 2em; /* Adjust the size as needed */
}

/* Optional: To ensure the custom styles are applied correctly */
.swal2-html-container {
    font-size: 2em !important; /* Use !important if necessary to override defaults */
}
</style>

<script>
$(document).ready(function() {
    $('#departmentAddForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        $.ajax({
            url: $(this).attr('action'), // Form action URL
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Success alert
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        width: '30%',
                        heightAuto: false,
                        timer: 2000,
                        customClass: {
                            popup: 'swal-custom-popup',
                            title: 'swal-custom-title',
                            text: 'swal-custom-text',
                            content: 'swal-custom-content',
                            confirmButton: 'swal-custom-confirm-btn'
                        }
                    }).then(() => {
                        // Optionally redirect after success
                        window.location.href = '<?= base_url("sys/admin/team"); ?>'; // Adjust as needed
                    });
                } else if (response.status === 'error') {
                    // Error alert
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        width: '30%',
                        heightAuto: false,
                        timer: 2000,
                        customClass: {
                            popup: 'swal-custom-popup',
                            title: 'swal-custom-title',
                            text: 'swal-custom-text',
                            content: 'swal-custom-content',
                            confirmButton: 'swal-custom-confirm-btn'
                        }
                    });
                }
            },
            error: function() {
                // Handle unexpected errors
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    width: '30%',
                    heightAuto: false,
                    timer: 2000,
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title',
                        text: 'swal-custom-text',
                        content: 'swal-custom-content',
                        confirmButton: 'swal-custom-confirm-btn'
                    }
                });
            }
        });
    });
});
</script>