<style>
  .resultsWidget {
    display: flex;
    justify-content: space-around;
    min-height: 200px;
  }
  .resultsWrapper {
    width: 48%;
  }
  .resultsContainer {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  #daysSoberResult { 
    background: url('https://livingsober.flywheelsites.com/wp-content/uploads/2018/11/LS_calculator_days@2x.png') no-repeat center;
    background-size: contain;
  }
  #moneyResult { 
    background: url('https://livingsober.flywheelsites.com/wp-content/uploads/2018/11/LS_calculator_money@2x.png') no-repeat center;
    background-size: contain;
  }
  .formWrapper {
    min-height: 120px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .daysSoberAction {
    display: flex;
    align-items: center;
    flex-direction: column;
  }
}
</style>

<div class="calculator">
  <div class="resultsWidget">
    <div class="resultsWrapper">
      <!-- days sober -->
      <div id="daysSoberResult" class="resultsContainer">
        <h2 class="result">0</h2>
      </div>
    </div>
      <div class="resultsWrapper">
      <!-- money saved -->
      <div id="moneyResult" class="resultsContainer">
        <h2 class="savings">$0.00</h2>
      </div>
    </div>
  </div>

  <!-- form -->
  <div class="formWrapper">
      <div id="recalcButton">
          <a href="javascript:;" target="_self" class="recalc fl-button" role="button">
              <span class="fl-button-text">Recalculate</span>
          </a>
      </div>

      <div class="daysSober">
        <form method="post" id="daysSoberForm" action="">
          <div id="daysSoberPre" class="daysSoberAction">
            <p>Enter in the date of your last drink.</p>
            <input type="text" name="daysSoberDatepicker" id="daysSoberDatepicker" value="" readonly="readonly" class="text" />
            <p>Enter your average weekly booze spend.</p>
            <input type="text" name="daysSoberMoney" id="daysSoberMoney" value="" class="text" />
            <div>
              <button class="btn btn-primary btn-red">Calculate</button>
              <div id="cancel" class="btn btn-secondary btn-red">Cancel</div>
            </div>
          </div>
      
        </form>  
      </div>
  </div>
</div>