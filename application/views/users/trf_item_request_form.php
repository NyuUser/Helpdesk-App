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

    .divider {
        height: 2px; /* Height of the line */
        background-color: #ccc; /* Color of the line */
        margin: 10px 0; /* Space above and below the line */
        width: 99%; /* Adjust width as needed */
        margin-left: auto; /* Center the line */
        margin-right: auto; /* Center the line */
    }

    .form-check-inline-custom { 
        display: inline-block; 
        align-items: center;
        margin-right: 20px; /* Adjust spacing as needed */ 
    }

    .form-check-label { 
        font-size: 1.35em;
        margin-left: 10px; /* Optional: space between checkbox and label */ 
        vertical-align: middle;
        height: 1.25em; 
    }

    .form-check-input { 
        width: 1.25em; 
        height: 1.25em; 
        vertical-align: middle;
    }

    /* Custom Checkbox Group */
    .custom-checkbox-group {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 15px; /* Space between each checkbox container */
    }

    /* Individual Checkbox Container */
    .checkbox-container {
        display: flex;
        align-items: center;
        min-width: 150px; 
    }

    /* Align checkbox and label */
    .checkbox-container input[type="checkbox"] {
        width: 1.25em; 
        height: 1.25em;
        margin: 0;
        vertical-align: middle; 
    }

    /* Label Margin */
    .checkbox-container label {
        margin-left: 8px; 
        margin-bottom: 0; /
        vertical-align: middle; 
        font-size: 1.30em;
    }

    /* Move only the label */
    .custom-label {
        padding-right: 10px; 
        font-weight: bold; 
        text-align: left; 
        min-width: 120px; 
        font-size: 1.30em;
    }

    .design-text {
        font-size: 18px; /* Adjust the font size as needed */
        font-weight: bold; /* Optional: makes the text bold */
        color: #333; /* Optional: changes text color */
    }
    
    .container {
        max-width: 100%; /* Adjust the width of the container */
    }

    .table {
        font-size: 1.5rem; /* Smaller font size for the table text */
        width: 100%; /* Ensure table width fits within the container */
    }

    .table th, .table td {
        padding: 8px; /* Reduce padding for a more compact table */
        text-align: center; /* Center text in table cells */
    }

    .table .form-control {
        font-size: 1.2rem; /* Increase the font size */
        padding: 8px; /* Optional: Adjust padding for better visibility */
    }
</style>

<div class="content-wrapper">
    <div class="container">
        <section class="content-header">
			<h1>
				Item Request Form Creation
			</h1>
			<ol class="breadcrumb">
				<li><a href=""><i class="fa fa-users"></i> Home</a></li>
				<li class="active">Item Request Form Creation</li>
			</ol>
		</section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
			                <li class="active"><a href="#msrf" data-toggle="tab">New Item Request Form Tickets</a></li>
			            </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="msrf">
                                <section id="new">
                                    <div class="row">
                                        <form action="<?= site_url('Main/user_creation_employee_request_form_pdf'); ?>" method="POST">
                                            <div class="col-md-12">
                                                <select name="trf_number" id="trf_number" class="form-control" required>
                                                    <option value="" disabled selected>Select Ticket Number</option>
                                                    <?php if (isset($ticket_numbers) && is_array($ticket_numbers)): ?>
                                                        <?php foreach ($ticket_numbers as $ticket): ?>
                                                            <option value="<?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES, 'UTF-8') ?>">
                                                                <?= htmlspecialchars($ticket['ticket_id'], ENT_QUOTES, 'UTF-8') ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="" disabled>No Tickets Available</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                                <!-- Checkboxes Section -->
                                            <div class="col-md-7 text-center" style="margin-top: 15px;">
                                                <div class="form-group d-flex justify-content-center">
                                                        <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LMI" id="checkbox_lmi">
                                                        <label for="checkbox_lmi" class="checkbox-label">LMI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="RGDI" id="checkbox_rgdi">
                                                        <label for="checkbox_rgdi" class="checkbox-label">RGDI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="LPI" id="checkbox_lpi">
                                                        <label for="checkbox_lpi" class="checkbox-label">LPI</label>
                                                    </div>
                                                    <div class="checkbox-inline custom-checkbox">
                                                        <input type="checkbox" name="irf_comp_checkbox_value[]" value="SV" id="checkbox_sv">
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
                        
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>LMI Item Code</label>
                                                    <input type="text" name="lmi_item_code" id="lmi_item_code" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Long Description (<small style="font-size:0.8em; color: red;">100 max char.</small>)</label>
                                                    <input type="text" name="long_description" id="long_description" value="" class="form-control select2" maxlength="100"> 
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Short Description (<small style="font-size:0.8em; color: red;">50 max char.</small>)</label>
                                                    <input type="text" name="short_description" id="short_description" value="" class="form-control select2" maxlength="50"> 
                                                </div>
                                            </div>

                                            <hr class="divider">

                                            <!-- First Row of Checkboxes -->
                                            <div class="container mt-4">
                                                <div class="row justify-content-center">
                                                    <!-- First Row of Checkboxes -->
                                                    <div class="col-md-12 d-flex justify-content-center" style="margin-top: 5px;">
                                                        <div class="form-group text-center">
                                                            <label for="" class="custom-label">Item Type:</label>
                                                            <div class="d-flex flex-wrap custom-checkbox-group justify-content-center">
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="inventory" value="inventory" id="inventory">
                                                                    <label for="inventory">Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="non_inventory" value="non_inventory" id="non-inventory">
                                                                    <label for="non-inventory">Non-Inventory</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="services" value="services" id="services">
                                                                    <label for="services">Services</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="charges" value="charges" id="charges">
                                                                    <label for="charges">Charges</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Second Row of Checkboxes -->
                                                    <div class="col-md-12 d-flex justify-content-center" style="margin-top: 5px;">
                                                        <div class="form-group text-center">
                                                            <label for="" class="custom-label">Item Account:</label>
                                                            <div class="d-flex flex-wrap custom-checkbox-group justify-content-center">
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="watsons" value="watsons" id="watsons">
                                                                    <label for="watsons">Watsons</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="other_accounts" value="other_accounts" id="other-accounts">
                                                                    <label for="other-accounts">Other Accounts</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="online" value="online" id="online">
                                                                    <label for="online">Online</label>
                                                                </div>
                                                                <div class="checkbox-container">
                                                                    <input type="checkbox" name="all_accounts" value="all_accounts" id="all-accounts">
                                                                    <label for="all-accounts">All Accounts</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="divider">

                                            <div class="container mt-4">
                                                <div class="row">
                                                    <!-- Trade and Non-Trade Checkboxes -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="trade" value="trade" id="trade">
                                                                <label for="trade">Trade</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="non_trade" value="non_trade" id="non-trade">
                                                                <label for="non-trade">Non-Trade</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Batch Required Checkboxes -->
                                                    <div class="col-md-6 d-flex align-items-center" style="margin-top: 5px;">
                                                        <div class="form-group d-flex flex-wrap align-items-center custom-checkbox-group">
                                                            <label for="" class="custom-label">Batch Required?</label>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="batch_required" value="yes" id="yes">
                                                                <label for="yes">YES</label>
                                                            </div>
                                                            <div class="checkbox-container">
                                                                <input type="checkbox" name="batch_required" value="no" id="no">
                                                                <label for="no">NO</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr class="divider">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Classification</label>
                                                    <input type="text" name="item_classification" id="item_classification" value="" class="form-control select2"> 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub-Classification</label>
                                                    <input type="text" name="item_sub_classification" id="positem_sub_classificationtion" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <input type="text" name="department" id="department" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Merch Category</label>
                                                    <input type="text" name="merch_cat" id="merch_cat" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Brand</label>
                                                    <input type="text" name="brand" id="brand" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Code</label>
                                                    <input type="text" name="supplier_code" id="supplier_code" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supplier Name</label>
                                                    <input type="text" name="supplier_name" id="supplier_name" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Class</label>
                                                    <input type="text" name="class" id="class" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tag</label>
                                                    <input type="text" name="tag" id="tag" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Source</label>
                                                    <input type="text" name="source" id="source" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>HS Code</label>
                                                    <input type="text" name="hs_code" id="hs_code" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Unit Cost</label>
                                                    <input type="text" name="unit_cost" id="unit_cost" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Selling Price</label>
                                                    <input type="text" name="selling_price" id="selling_price" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <hr class="divider"> 

                                            <div class="design-text text-center">
                                               For Non-Invertoriable Items (Please Fill Up)
                                            </div>

                                            <hr class="divider">
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Major Item Group</label>
                                                    <input type="text" name="major_item_group" id="major_item_group" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Item Sub. Group</label>
                                                    <input type="text" name="item_sub_group" id="item_sub_group" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Account Type</label>
                                                    <input type="text" name="account_type" id="account_type" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <hr class="divider"> 

                                            <div class="design-text text-center">
                                               GL Set-Up
                                            </div>

                                            <hr class="divider">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales</label>
                                                    <input type="text" name="sales" id="sales" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Return</label>
                                                    <input type="text" name="sales_return" id="sales_return" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchases</label>
                                                    <input type="text" name="purchases" id="purchases" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Purchase Return</label>
                                                    <input type="text" name="purchase_return" id="purchase_return" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>CGS</label>
                                                    <input type="text" name="cgs" id="cgs" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Inventory</label>
                                                    <input type="text" name="inventory" id="inventory" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sales Disc.</label>
                                                    <input type="text" name="sales_disc" id="sales_disc" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GL Department</label>
                                                    <input type="text" name="gl_dept" id="gl_dept" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <div class="container mt-4">
                                                <table class="table table-bordered text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>UOM</th>
                                                            <th>Barcode</th>
                                                            <th>Length (cm)</th>
                                                            <th>Height (cm)</th>
                                                            <th>Width (cm)</th>
                                                            <th>Weight (grams)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Row 1 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row1_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col6" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 2 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row2_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col6" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 3 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row3_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col6" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 4 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row4_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col6" placeholder=""></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Capacity per Pallet <small style="font-size:0.8em;">(Smallest OUM (PCS))</small></label>
                                                    <input type="text" name="position" id="position" value="" class="form-control select2"> 
                                                </div>
                                            </div>

                                            <hr class="divider"> 

                                            <div class="design-text text-center">
                                               Warehouse Set-Up
                                            </div>

                                            <hr class="divider">

                                            <div class="container mt-4">
                                                <table class="table table-bordered text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>Warehouse</th>
                                                            <th>Warehouse No.</th>
                                                            <th>Storage Location</th>
                                                            <th>Storage Type</th>
                                                            <th>Fixed Bin</th>
                                                            <th>Min. Qty</th>
                                                            <th>Max Qty</th>
                                                            <th>Replen. Qty</th>
                                                            <th>Control Qty</th>
                                                            <th>Round Qty</th>
                                                            <th>UOM</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Row 1 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row1_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col6" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col7" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col8" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col9" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col10" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row1_col11" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 2 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row2_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col6" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col7" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col8" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col9" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col10" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row2_col11" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 3 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row3_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col6" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col7" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col8" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col9" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col10" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row3_col11" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 4 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row4_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col6" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col7" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col8" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col9" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col10" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row4_col11" placeholder=""></td>
                                                        </tr>
                                                        <!-- Row 5 -->
                                                        <tr>
                                                            <td><input type="text" class="form-control" name="row5_col1" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col2" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col3" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col4" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col5" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col6" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col7" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col8" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col9" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col10" placeholder=""></td>
                                                            <td><input type="text" class="form-control" name="row5_col11" placeholder=""></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Requested By</label>
                                                    <input type="text" name="requested_by" id="requested_by" value="<?php echo htmlspecialchars($user_details['fname']. " " . $user_details['mname']. " ". $user_details['lname']); ?>" class="form-control select2" required readonly> 
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
