/** @format */

import {AppRegistry} from 'react-native';
import App from './src/router';
// import Tabs from './src/Tabs';
import {name as appName} from './app.json';
import { I18nManager } from 'react-native';
I18nManager.forceRTL(true);
I18nManager.allowRTL(true);
//import RNRestart from 'react-native-restart'; // Import package from node modules
 
// Immediately reload the React Native Bundle
//RNRestart.Restart();
AppRegistry.registerComponent(appName, () => App);


