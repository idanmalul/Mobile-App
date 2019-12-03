import { Alert,Platform,Linking } from 'react-native';
import AppUpdate from 'react-native-appupdate';

const appUpdate = new AppUpdate({
  iosAppId: '1450781882',
  // apkVersionUrl: 'http://10k.tempurl.co.il/version.json',
  needUpdateApp: (needUpdate) => {
    if(Platform.OS=='ios'){
        Alert.alert(
          'Update Available',
          'New version released, do you want to update? ',
          [
            {text: 'Cancel', onPress: () => {}},
            {text: 'Update', onPress: () => (Platform.OS=='ios') ? needUpdate(true) : needUpdate(true) }
            // {text: 'Update', onPress: () => (Platform.OS=='ios') ? needUpdate(true) : Linking.openURL("market://details?id=com.tenkclub") }
          ]
        );
    }
  }, 
  forceUpdateApp: () => {
    console.log("Force update will start")
  },
  notNeedUpdateApp: () => {
    console.log("App is up to date")
  },
  downloadApkStart: () => { console.log("Start") },
  downloadApkProgress: (progress) => { console.log(`Downloading android apk ${progress}%...`) },
  downloadApkEnd: () => { console.log("End") },
  onError: () => { console.log("downloadApkError") }

  // needAndroidUpdate(){
  //   console.log("downloadApkError");
  // }
});

appUpdate.checkUpdate();

export default appUpdate;