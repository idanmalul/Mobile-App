/**
 * @format
 */

import {AppRegistry} from 'react-native';
import App from './src/router';
// import Tabs from './src/Tabs';
import {name as appName} from './app.json';
import { I18nManager } from 'react-native';
// import AppForceUpdate from './src/screens/AppForceUpdate';
// import AppUpdate from './src/screens/AppForceUpdate';
I18nManager.forceRTL(false);
I18nManager.allowRTL(false); 
// import AppForceUpdate from './screens/AppForceUpdate';
//import RNRestart from 'react-native-restart'; // Import package from node modules
 
// Immediately reload the React Native Bundle
//RNRestart.Restart();
AppRegistry.registerComponent(appName, () => App);


