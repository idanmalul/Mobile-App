package com.tenkclub;

import android.app.Application;

import com.facebook.react.ReactApplication;
import com.reactnativecommunity.asyncstorage.AsyncStoragePackage;
import com.tron.ReactNativeWheelPickerPackage;
import net.mikehardy.rnupdateapk.RNUpdateAPKPackage;
import com.artirigo.fileprovider.RNFileProviderPackage;
import com.rnfs.RNFSPackage;
import com.parryworld.rnappupdate.RNAppUpdatePackage;
import org.wonday.pdf.RCTPdfView;
import com.RNFetchBlob.RNFetchBlobPackage;
import io.invertase.firebase.RNFirebasePackage;
import com.avishayil.rnrestart.ReactNativeRestartPackage;
import com.swmansion.gesturehandler.react.RNGestureHandlerPackage;
import com.oblador.vectoricons.VectorIconsPackage;
import com.brentvatne.react.ReactVideoPackage;
import com.BV.LinearGradient.LinearGradientPackage;
import im.shimo.react.cookie.CookieManagerPackage;
import com.facebook.react.ReactNativeHost;
import com.facebook.react.ReactPackage;
import com.facebook.react.shell.MainReactPackage;
import com.facebook.soloader.SoLoader;

import java.util.Arrays;
import java.util.List;

import io.invertase.firebase.RNFirebasePackage;
import io.invertase.firebase.messaging.RNFirebaseMessagingPackage;                       
import io.invertase.firebase.notifications.RNFirebaseNotificationsPackage;
public class MainApplication extends Application implements ReactApplication {

  private final ReactNativeHost mReactNativeHost = new ReactNativeHost(this) {
    @Override
    public boolean getUseDeveloperSupport() {
      return BuildConfig.DEBUG;
    }

    @Override
    protected List<ReactPackage> getPackages() {
      return Arrays.<ReactPackage>asList(
          new MainReactPackage(),
            new AsyncStoragePackage(),
            new ReactNativeWheelPickerPackage(),
            new RNUpdateAPKPackage(),
            new RNFileProviderPackage(),
            new RNFSPackage(),
            new RNAppUpdatePackage(),
            new RCTPdfView(),
            new RNFetchBlobPackage(),
           
            new ReactNativeRestartPackage(),
            new RNGestureHandlerPackage(),
            new VectorIconsPackage(),
            new ReactVideoPackage(),
            new LinearGradientPackage(),
            new RNFirebasePackage(),
            new RNFirebaseMessagingPackage(),
            new RNFirebaseNotificationsPackage(),
            new CookieManagerPackage()
      );
    }

    @Override
    protected String getJSMainModuleName() {
      return "index";
    }
  };

  @Override
  public ReactNativeHost getReactNativeHost() {
    return mReactNativeHost;
  }

  @Override
  public void onCreate() {
    super.onCreate();
    SoLoader.init(this, /* native exopackage */ false);
  }

  // @Override
  // public String getFileProviderAuthority() {
  //       return "com.tenkclub.provider";
  // }
}
