<!-- days sober -->
<div id="daysSoberResult">
	    <span class="result">0</span>
</div>

<!-- money saved -->
<div id="moneyResult">
  <span class="savings">$0.00</span>
</div>

<!-- form -->
<div class="formWrapper">
    <div id="recalcButton">
        <a href="javascript:;" class="recalc">Recalculate</a>
    </div>
    
    <div class="daysSober">
    	<form method="post" id="daysSoberForm" action="">
        <div id="daysSoberPre" class="daysSoberAction">
          <input type="text" name="daysSoberDatepicker" id="daysSoberDatepicker" value="" readonly="readonly" class="text" />
          <input type="text" name="daysSoberMoney" id="daysSoberMoney" value="" class="text" />
          <button class="btn btn-primary btn-red">Calculate</button>
          <div id="cancel" class="btn btn-secondary btn-red">Cancel</div>
        </div>
    
      </form>  
    </div>
</div>