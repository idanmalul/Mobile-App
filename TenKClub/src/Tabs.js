import React from 'react';
import {
  Image,
  I18nManager
} from 'react-native';
I18nManager.forceRTL(true); 
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';
import Home from './screens/storyList';
import Search from './screens/searchEvents';
import MyEvent from './screens/myEventList';
import Notification from './screens/myNotifications';
const MyNavigator = createMaterialBottomTabNavigator({
  
  Home, 
  Search,
  Notification,
  MyEvent
  
}, {
  initialRouteName: 'Home',
  activeTintColor: '#f0edf6',
  inactiveTintColor: '#a7a7a7',
  // activeTintColor: '#e91e63',
  // inactiveTintColor: '#000fff',
  swipeEnabled: true,
  barStyle: { backgroundColor: '#272B2E' },
  navigationOptions: ({ navigation }) => ({
    tabBarIcon: ({ focused, tintColor }) => {
      const { routeName } = navigation.state;
      return <Image style={{
        width: 28,
        height: 28,
      }} />;
    },
    
  }),
});
export default MyNavigator;