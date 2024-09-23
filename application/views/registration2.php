<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
</head>
<body>
    <h1>HELLO WORLD</h1>

    <div class="row">
        <label>Department</label>
        <select name="dept_name" id="dept_name">
            <option value="">Select Dept</option>
            <?php 
            foreach($get_departments as $stuu)
            {
                ?>
                <option value="<?php echo $stuu['recid']; ?>"><?php echo $stuu['dept_desc']; ?></option>
            <?php }
            ?>
        </select>
    </div>
</body>
</html>