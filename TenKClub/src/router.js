import React, { Component } from 'react';
// import {StyleSheet, Text, View,Button} from 'react-native';  

import { Easing,Animated,StyleSheet, Text, View,Button } from 'react-native';
import Splash from './screens/Splash';
import Login from './screens/Login';
import Home from './screens/Home';
import Profile from './screens/Profile';
import storyList from './screens/storyList';
// import storyDetail from "./screens/storyDetails";
import Tabs from './Tabs';
import GetNoti_Token from './screens/GetNoti_Token';
import searchEvents from './screens/searchEvents';
import Registration from './screens/Registration';
import Favourites from './screens/Favourites';
import FlatDemo from './screens/flatListDemo';
import Settings from './screens/Settings';
import Privacy_and_Security_Help from './screens/Privacy_and_Security_Help';
import Helpcenter from './screens/Helpcenter';
import ReportProblem from './screens/ReportProblem'
import About from './screens/About'
import OfferDetails from './screens/OfferDetails'
import Notification_list from './screens/Notification_list'
// import Dashboard from './screens/Dashboard'
import logged_in from './screens/logged_in'
import OTPVerification from './screens/OTPVerification'
import twoFactorOTPVerification from './screens/twoFactorOTPVerification'
import GuidelineScreen_1 from './screens/GuidelineScreen_1'
import GuidelineScreen_2 from './screens/GuidelineScreen_2'
import GuidelineScreen_3 from './screens/GuidelineScreen_3'
import GuidelineScreen_4 from './screens/GuidelineScreen_4'
import GuidelineScreen_5 from './screens/GuidelineScreen_5'
import LoginScreen from './screens/LoginScreen'
import PaymentScreen_1 from './screens/PaymentScreen_1'
import ForgotPass from './screens/ForgotPass'

// import { DrawerNavigator, StackNavigator } from "react-navigation";
import {createStackNavigator,createAppContainer,createDrawerNavigator} from "react-navigation";
import { createMaterialBottomTabNavigator } from 'react-navigation-material-bottom-tabs';  
import Icon from 'react-native-vector-icons/Ionicons';  

// import AppUpdate from './screens/AppUpdateByFileprovider';
// import AppForceUpdate from './screens/AppForceUpdate';
// I18nManager.forceRTL(true);
let SlideFromRight = (index, position, width) => {
    const inputRange = [index - 1, index, index + 1];
    const translateX = position.interpolate({
      inputRange: [index - 1, index, index + 1],
      outputRange: [width, 0, 0]
    })
    const slideFromRight = { transform: [{ translateX }] }
    return slideFromRight
  };

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
    // storyDetail: {
    //     screen: storyDetail,
    //     screenProps: {
    //         name: 'storyDetail',
    //     }
    // },
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
    // TabNavigator,
    Splash: {
        screen: Splash
    },
    Home: {
        screen: Home
    },
    Login: {
        screen: Login
    },
    Registration: {
        screen: Registration
    },
    Favourites: {
        screen: Favourites
    },
    FlatDemo: {
        screen: FlatDemo
    },
    Profile: {
        screen: Profile
    },
    storyList: {
        screen: storyList
    },
    // storyDetail: {
    //     screen: storyDetail,
    //     screenProps: {
    //         name: 'storyDetail',
    //     }
    // },
    Tabs: {
        screen: Tabs
    },
    GetNoti_Token: {
        screen: GetNoti_Token
    },
    searchEvents: {
        screen: searchEvents
    },
    Settings: {
        screen: Settings
    },
    Privacy_and_Security_Help: {
        screen: Privacy_and_Security_Help
    },
    Helpcenter: {
        screen: Helpcenter
    },
    ReportProblem: {
        screen: ReportProblem
    },
    About: {
        screen: About
    },
    OfferDetails: {
        screen: OfferDetails
    },
    Notification_list: {
        screen: Notification_list
    },
    // Dashboard: {
    //     screen: Dashboard
    // },
    logged_in: {
        screen: logged_in
    },
    OTPVerification: {
        screen: OTPVerification
    },
    twoFactorOTPVerification: {
        screen: twoFactorOTPVerification
    },
    GuidelineScreen_1: {
        screen: GuidelineScreen_1
    },
    GuidelineScreen_2: {
        screen: GuidelineScreen_2
    },
    GuidelineScreen_3: {
        screen: GuidelineScreen_3
    },
    GuidelineScreen_4: {
        screen: GuidelineScreen_4
    },
    GuidelineScreen_5: {
        screen: GuidelineScreen_5
    },
    LoginScreen: {
        screen: LoginScreen
    },
    PaymentScreen_1: {
        screen: PaymentScreen_1
    },
    ForgotPass: {
        screen: ForgotPass
    }
    // AppUpdate: {
    //     screen: AppUpdate
    // },
},
    {
        cardStyle: { backgroundColor:"#000000" }, 
        initialRouteName: 'Splash',
        headerMode: 'none',
        navigationOptions: {
            gesturesEnabled: false,
        },
        // transitionConfig: () => ({
        //     transitionSpec: {
        //         duration: 300,
        //         easing: Easing.out(Easing.poly(4)),
        //         timing: Animated.timing,
        //         useNativeDriver: true,
        //       },
        //       screenInterpolator: (sceneProps) => {
        //         const { layout, position, scene } = sceneProps;
        //         const width = layout.initWidth;
        //         const { index, route } = scene
        //         const params = route.params || {}; // <- That's new
        //         const transition = params.transition || 'default'; // <- That's new
        //         return {
                 
        //           default: SlideFromRight(index, position, width),
        //         }[transition];
        //       },
        // }),
    }
    );


    const TabNavigator = createMaterialBottomTabNavigator(  
        {
            Map: { screen: Home },
    DetailStack: { screen: Clubapp},  
            Home: { screen: Home,  
                navigationOptions:{  
                    tabBarLabel:'Home',  
                    tabBarIcon: ({ tintColor }) => (  
                        <View>  
                            <Icon style={[{color: tintColor}]} size={25} name={'ios-home'}/>  
                        </View>),  
                }  
            },  
            Profile: { screen: Profile,  
                navigationOptions:{  
                    tabBarLabel:'Profile',  
                    tabBarIcon: ({ tintColor }) => (  
                        <View>  
                            <Icon style={[{color: tintColor}]} size={25} name={'ios-person'}/>  
                        </View>),  
                    activeColor: '#f60c0d',  
                    inactiveColor: '#f65a22',  
                    barStyle: { backgroundColor: '#f69b31' },  
                }  
            },  
            Image: { screen: Notification_list,  
                navigationOptions:{  
                    tabBarLabel:'History',  
                    tabBarIcon: ({ tintColor }) => (  
                        <View>  
                            <Icon style={[{color: tintColor}]} size={25} name={'ios-images'}/>  
                        </View>),  
                    activeColor: '#615af6',  
                    inactiveColor: '#46f6d7',  
                    barStyle: { backgroundColor: '#67baf6' },  
                }  
            },  
            Cart: {  
                screen: PaymentScreen_1,  
                navigationOptions:{  
                    tabBarLabel:'Cart',  
                    tabBarIcon: ({ tintColor }) => (  
                        <View>  
                            <Icon style={[{color: tintColor}]} size={25} name={'ios-cart'}/>  
                        </View>),  
                }  
            },  
        },  
        {  
          initialRouteName: "Home",  
          activeColor: '#f0edf6',  
          inactiveColor: '#226557',  
          barStyle: { backgroundColor: '#3BAD87' },  
        },  
    );  
    
      
    // export default createAppContainer(TabNavigator); 
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

