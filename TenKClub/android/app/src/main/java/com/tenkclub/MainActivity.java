package com.tenkclub;

import com.facebook.react.ReactActivity;

public class MainActivity extends ReactActivity {

    /**
     * Returns the name of the main component registered from JavaScript.
     * This is used to schedule rendering of the component.
     */
    @Override
    protected String getMainComponentName() {
        // I18nUtil sharedI18nUtilInstance = I18nUtil.getInstance();
        // sharedI18nUtilInstance.allowRTL(this, true);
        return "TenKClub";
    }

    
}
