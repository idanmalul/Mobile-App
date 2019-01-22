import React, { Component } from 'react';
//import { I18nManager } from 'react-native';
import Splash from './screens/Splash';
import Login from './screens/Login';
// import Home from './screens/Home';
import Profile from './screens/Profile';
import storyList from './screens/storyList';
import storyDetail from "./screens/storyDetails";
import Tabs from './Tabs';
import GetNoti_Token from './screens/GetNoti_Token';
import searchEvents from './screens/searchEvents';

// import { DrawerNavigator, StackNavigator } from "react-navigation";
import {createStackNavigator,createAppContainer,createDrawerNavigator} from "react-navigation";
// I18nManager.forceRTL(true);
const HomeDrawer = createDrawerNavigator({
    // Home: {
    //     screen: Home
    // },

    
    Login: {
        screen: Login
    }
    ,
    Profile: {
        screen: Profile
    },
    storyList: {
        screen: storyList
    },
    storyDetail: {
        screen: storyDetail,
        screenProps: {
            name: 'storyDetail',
        }
    },
    // Tabs: {
    //     screen: Tabs
    // },
},
    {
        Name: 'Main',
        contentComponent: props => <SideMenu {...props} />,
        drawerWidth: 270,
        header: null,
        headerMode: 'none',
    }
);

const Clubapp = createStackNavigator({
    Splash: {
        screen: Splash
    },
    // Home: {
    //     screen: Home
    // },
    Login: {
        screen: Login
    }
    ,
    Profile: {
        screen: Profile
    },
    storyList: {
        screen: storyList
    },
    storyDetail: {
        screen: storyDetail,
        screenProps: {
            name: 'storyDetail',
        }
    },
    Tabs: {
        screen: Tabs
    },
    GetNoti_Token: {
        screen: GetNoti_Token
    },
    searchEvents: {
        screen: searchEvents
    },
},
    {
        initialRouteName: 'Splash',
        headerMode: 'none',
        navigationOptions: {
            gesturesEnabled: false,
        },
    }
    );
const PrimaryNav = createAppContainer(Clubapp);
/*
const PrimaryNav = createAppContainer({
    loginStack: { screen: Clubapp },
    drawerStack: { screen: HomeDrawer }
}, {
        // Default config for all screens
        headerMode: 'none',
        title: 'Main',
        initialRouteName: 'loginStack'
    })
*/
export default PrimaryNav

console.disableYellowBox = true;

