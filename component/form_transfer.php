<div class="overall_form">
    <div id="close_form" class="close_form">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </div>
    <div class="scroll_bar">
        <form action="" id="overall_form_data">
                    <input type="hidden" name="decide" id="decide" value="0">
            <div class="row">
                <h1 style="width:100%;text-align:center;margin-bottom:10px;">Transfer Table Form</h1>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="admission">Admission</label>
                </div>
                <div class="col2">
                    <input type="number" id="admission" name="admission" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="stu_name">Student Name</label>
                </div>
                <div class="col2">
                    <input type="text" id="stu_name" name="stu_name" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="class">Class</label>
                </div>
                <div class="col2">
                    <select name="class" id="class" required>   
                        <option value="">--select--</option>
                        <option value="LKG">LKG</option>        
                        <option value="UKG">UKG</option>
                        <option value="I">I</option>
                        <option value="II">II</option>
                        <option value="III">III</option>
                        <option value="IV">IV</option>
                        <option value="V">V</option>
                        <option value="VI">VI</option>
                        <option value="VII">VII</option>
                        <option value="VIII">VIII</option>
                        <option value="IX">IX</option>
                        <option value="X">X</option>
                        <option value="XIAC">XIAC</option>
                        <option value="XIDE">XIDE</option>
                        <option value="XIIAC">XIIAC</option>
                        <option value="XIIDE">XIIDE</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="section">Section</label>
                </div>
                <div class="col2">
                    <input type="text" id="section" name="section" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="year">PassedOut Year</label>
                </div>
                <div class="col2">
                    <input type="text" id="year" name="year" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="pending">Pending</label>
                </div>
                <div class="col2">
                    <input type="number" id="pending" name="pending" required>
                </div>
            </div>
            <div class="row">
                <div class="overall_save">
                    <?php
                    include "./component/save_btn.html";
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>