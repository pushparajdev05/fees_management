<div class="overall_form">
    <div id="close_form" class="close_form">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </div>
    <div class="scroll_bar">
        <form action="" id="overall_form_data">
                    <input type="hidden" name="decide" id="decide" value="0">
            <div class="row">
                <h1 style="width:100%;text-align:center;margin-bottom:10px;">Overall Table Form</h1>
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
                    <label for="terms">Term fees</label>
                </div>
                <div class="col2 terms_val">

                    <div class="terms">
                        <!-- <label for="term1">I</label> -->
                        <input type="number" id="term1" name="term1" required placeholder="Term I">
                    </div>

                    <div class="terms">
                        <!-- <label for="term2">II</label> -->
                        <input type="number" id="term2" name="term2" required placeholder="Term II">
                    </div>

                    <div class="terms">
                        <!-- <label for="term3">III</label> -->
                        <input type="number" id="term3" name="term3" required placeholder="Term III">
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="paid_date">Paid Date</label>
                </div>
                <div class="col2">
                    <input type="date" id="paid_date" name="paid_date">
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="scholarship">Scholarship</label>
                </div>
                <div class="col2">
                    <select name="scholarship" id="scholarship">
                        <option value="no">no</option>
                        <option value="yes">yes</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="scholarship_amt">Scholarship amount</label>
                </div>
                <div class="col2">
                    <input type="number" class="scholarship_amt" id="scholarship_amt" name="scholarship_amt" value="0" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="write_off">Write Off</label>
                </div>
                <div class="col2">
                    <input type="number" id="write_off" name="write_off" value="0" required>
                </div>
            </div>
            <div class="row">
                <div class="col1">
                    <label for="total_receivable">Total Receivable</label>
                </div>  
                <div class="col2">
                    <input type="number" id="total_receivable" name="total_receivable">
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