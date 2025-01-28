<div id="form1">
    <form action="" method="post" id="collection_data">
        <div class="scroll_bar">
            <div id="form_element">
                <h1 class="h1" id="form_head">Daily Fees Collection</h1>
                <div id="elements">
                <input type="hidden" name="decide" id="decide">
            <div class="input row">
                <div class="col1">
                    <label for="admin">Admission No</label> 
                </div>
                <div class="col2">
                    <input name="admission" id="admin" type="number" required>
                </div>
            </div>
            <div class="input row">
                <div class="col1">
                    <label for="std">Student Name</label>
                </div>
                <div class="col2">
                    <input name="std" id="std" type="text" required>
                </div>
            </div>
            <div class="input row">
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
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>
            </div>
            <div class="input row">
                <div class="col1">
                    <label for="section">Section</label>
                </div>
                <div class="col2">
                    <input name="section" id="section" type="text" required>
                </div>
            </div>
            <div class="input row" id="term_input">
                <div class="col1">
                    <label for="type">Type of fees</label>
                </div>
                <div class="col2 types">
                    <select name="type" id="type" class="type" multiple required>
                        <option value="" style="border: none;">select the class to get fees type</option>
                    </select>
                    <div class="terms">
                        <input type="number" name="t1" id="t1" placeholder="term1">
                        <input type="number" name="t2" id="t2" placeholder="term2">
                        <input type="number" name="t3" id="t3" placeholder="term3">
                    </div>
                </div>
            </div>
            <div class="input row">
                <div class="col1">
                    <label for="amt">Amount</label>
                </div>
                <div class="col2">
                    <input name="amt" id="amt" type="number" readonly>
                </div>
            </div>
            <div class="input row">
                <div class="col1">
                    <label for="payment">Payment Mode</label>
                </div>
                <div class="col2">
                    <select name="payment" id="payment">
                        <option value="0">cash</option>
                        <option value="1">cheque/online</option>
                    </select>
                </div>
            </div>
        </div>
            </div>
        </div>
        <div id="btn_option">
            <div id="btn">
                <div class="print"> 
                    <?php
                        include "print_btn.html";
                    ?>
                </div>
                <div class="submit">
                    <?php
                        include "save_btn.html";
                    ?>
                </div>
                <div class="close">
                    <?php
                        include "close_btn.html";
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>