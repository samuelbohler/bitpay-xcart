<div class="bitpay configured">

  <table cellspacing="1" cellpadding="5" class="settings-table">

    <tr>
      <td class="setting-name"><label for="settings_riskSpeed">{t(#Risk/Speed#)}</label></td>
      <td>
        <input type="text" id="settings_prefix" value="{paymentMethod.getSetting(#riskSpeed#)}" name="settings[riskSpeed]" />
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>
        <div class="buttons">
          <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" style="regular-main-button" />
        </div>
      </td>
    </tr>

  </table>

</div>
