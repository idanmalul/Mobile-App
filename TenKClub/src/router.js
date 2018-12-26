import React, { Component } from 'react';

import Splash from './screens/Splash';
import Login from './screens/Login';
// import Home from './screens/Home';
import Profile from './screens/Profile';
import storyList from './screens/storyList';
import storyDetail from './screens/storyDetail';
// import SideMenu from './screens/SideMenu';

import { DrawerNavigator, StackNavigator } from "react-navigation";

const HomeDrawer = DrawerNavigator({
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
    }
}
,
    {
        Name: 'Main',
        contentComponent: props => <SideMenu {...props} />,
        drawerWidth: 270,
        header: null,
        headerMode: 'none',
    }
);

const Clubapp = StackNavigator({
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
    }
},
    {
        initialRouteName: 'Splash',
        headerMode: 'none',
        navigationOptions: {
            gesturesEnabled: false,
        },
    });
const PrimaryNav = StackNavigator({
    loginStack: { screen: Clubapp },
    drawerStack: { screen: HomeDrawer }
}, {
        // Default config for all screens
        headerMode: 'none',
        title: 'Main',
        initialRouteName: 'loginStack'
    })



export default PrimaryNav

console.disableYellowBox = true;