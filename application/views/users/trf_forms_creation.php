<style>
    .custom-checkbox {
        display: inline-flex;
        align-items: center; /* Centers checkbox and label vertically */
        margin: 0 20px; /* Space between each checkbox item */
    }

    .custom-checkbox input[type="checkbox"] {
        width: 20px;
        height: 18px; /* Larger checkbox size */
        margin: 0; /* Reset any default margin */
    }

    .checkbox-label {
        font-size: 22px; /* Larger label text */
        margin-left: 25px; /* Space between checkbox and label text */
        line-height: 2; /* Ensure label text aligns vertically */
    }

    .custom-form-group {
        display: flex;
        align-items: center;
        margin-top: 10px; /* Adjust this value as needed */
    }

    .custom-form-group label {
        margin-right: 10px;
        margin-bottom: 0; /* Ensures no bottom margin */
    }

    .custom-form-group input[type="date"] {
        flex: 1; /* Ensures the input field takes up available space */
    }

</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Customer Request Form TMS Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Concern Tickets</li>
				<li class="active">TRACC Request Form Tickets</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">Customer Request Form Tickets</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/'); ?>" method="POST">
                                            <div class="col-md-12">
                                                <select name="trf_number" id="trf_number" class="form-control" required>
                                                    <option value="" disabled selected>Select Ticket Number</option> 
                                                    <?php if(isset($ticket_numbers) && is_array($ticket_numbers)): ?> 
                                                        <?php foreach ($ticket_numbers as $ticket): ?> 
                                                            <option value="<?= $ticket['ticket_id'] ?>"><?= $ticket['ticket_id'] ?></option>
                                                        <?php endforeach; ?> 
                                                    <?php else: ?> 
                                                            <option value="" disabled>No Tickets Available</option> 
                                                    <?php endif; ?> 
                                                </select>                                        
			                    			</div>

                                               
                                            <div class="row">
                                                <!-- Checkboxes Section -->
                                                <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                    <div class="form-group d-flex justify-content-center">
                                                            <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="comp_checkbox_value[]" value="LMI" id="checkbox_lmi">
                                                            <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi">
                                                            <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="comp_checkbox_value[]" value="LPI" id="checkbox_lpi">
                                                            <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                        </div>
                                                        <div class="checkbox-inline custom-checkbox">
                                                            <input type="checkbox" name="comp_checkbox_value[]" value="SV" id="checkbox_sv">
                                                            <label for="checkbox_sv" class="checkbox-label">SVI</label>
                                                        </div>
                                                    </div>
                                                </div>
        
                                                <!-- Date Input Section (Label beside the date input) -->
                                                <div class="col-md-3" style="margin-top: 15px;">
                                                    <div class="form-group d-flex align-items-center custom-form-group">
                                                        <label for="date" class="mr-2">Date</label>
                                                        <input type="date" name="date" id="date" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>          

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Customer Code</label>
                                                    <input type="text" name="customer_code" id="customer_code" value="" class="form-control select2" required> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>TIN No</label>
                                                    <input type="text" name="tin_no" id="tin_no" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Customer Name</label>
                                                    <input type="text" name="customer_name" id="customer_name" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Terms</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Customer Address</label>
                                                    <input type="text" name="customer_address" id="customer_address" value="" class="form-control select2" required>
                                                    <small class="form-text text-muted">Building/House No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Street/Phase&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Barangay&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;City/Municipality&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Postal Code&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Province&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Region&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Country</small> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact Person</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Price List</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Office Tel. No.</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Payment Group</label>
                                                    <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Contact No.</label>
                                                        <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Territory</label>
                                                        <input type="text" name="terms" id="terms" value="" class="form-control select2" required> 
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <!-- Column for stacked checkboxes -->
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>Options</label>
                                                        <div>
                                                            <div>
                                                                <input type="checkbox" id="option1" name="option1">
                                                                <label for="option1">Option 1</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" id="option2" name="option2">
                                                                <label for="option2">Option 2</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" id="option3" name="option3">
                                                                <label for="option3">Option 3</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Column for side-by-side checkboxes -->
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label>More Options</label>
                                                        <div class="d-flex">
                                                            <div class="me-3">
                                                                <input type="checkbox" id="option4" name="option4">
                                                                <label for="option4">Option 4</label>
                                                            </div>
                                                            <div>
                                                                <input type="checkbox" id="option5" name="option5">
                                                                <label for="option5">Option 5</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="box-body pad">
                                                        <button id="form-add-submit-button" type="submit" class="btn btn-primary">Submit Tickets</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<script>

    $(document).ready(function() {
        // Set the current date in YYYY-MM-DD format
        var today = new Date().toISOString().split('T')[0];
        $('#date').val(today);
    });

</script>