import React from 'react';
import {
  Image,
  I18nManager
} from 'react-native';
I18nManager.forceRTL(true); 
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';
// import Home from './screens/storyList';
import Home from './screens/Home';
// import Search from './screens/searchEvents';
// import MyEvent from './screens/myEventList';

// import Notification from './screens/myNotifications';
import Notification from './screens/Notification_list';
import PaymentScreen from './screens/PaymentScreen_1';
import Profile from './screens/Registration';

const MyNavigator = createMaterialBottomTabNavigator({
  
  Home, 
  Notification,
  PaymentScreen,
  Profile
  
}, {
  initialRouteName: 'Home',
  activeTintColor: '#000000',//'#f0edf6',
  inactiveTintColor: '#000000',//'#a7a7a7',
  // activeTintColor: '#e91e63',
  // inactiveTintColor: '#000fff',
  swipeEnabled: false,
  shifting: false,
  barStyle: { backgroundColor: '#aaaaaa' }, // #272B2E  // #999999
  navigationOptions: ({ navigation }) => ({
    tabBarIcon: ({ focused, tintColor }) => {
      const { routeName } = navigation.state;
      return <Image style={{
        width: 24,
        height: 24,
      }} />;
    },
      style: {
        position:'relative',
        bottom:0
        // padding: 0,
        // height: 10,
      },
    // tabBarOptions: {
    //   style: {
    //     padding: 0,
    //     height: 0,
    //   },
    // },
    
  }),
});
export default MyNavigator;