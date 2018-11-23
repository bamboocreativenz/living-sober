<style>
  .resultWrapper {
    display: flex;
    justify-content: space-around;
  }
  
</style>

<div class="calculator">
  <div class="resultWrapper">
    <!-- days sober -->
    <div id="daysSoberResult">
          <span class="result">0</span>
    </div>

    <!-- money saved -->
    <div id="moneyResult">
      <span class="savings">$0.00</span>
    </div>
  </div>

  <!-- form -->
  <div class="formWrapper">
      <div id="recalcButton" style="text-align:center;">
          <a href="javascript:;" target="_self" class="recalc fl-button" role="button">
              <span class="fl-button-text">Recalculate</span>
          </a>
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
</div>