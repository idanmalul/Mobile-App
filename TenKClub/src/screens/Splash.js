import React, { Component } from 'react';
import { connect } from 'react-redux';
import firebase from 'react-native-firebase';
import base64 from 'react-native-base64';
import RNRestart from 'react-native-restart'; // Import package from node modules
import {
    StyleSheet,
    View,
    StatusBar, ImageBackground, AsyncStorage, Dimensions, Image, NetInfo, I18nManager,Alert,Platform
} from 'react-native';
I18nManager.forceRTL(true); 
var width = Dimensions.get('window').width; //full width
var height = Dimensions.get('window').height; //full height
import { Images, Colors } from '../themes'
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
const isRTL = I18nManager.isRTL;
export default class Splash extends React.Component {
    static navigationOptions = {
        header: null
    };
    constructor(props) {
        super(props);
        if(isRTL == false){
            //alert("first");
            //this.props.dispatch(setAppLayoutDirection('rtl'));
            RNRestart.Restart();
          }
 
    }

    async componentDidMount() {
        this.checkPermission();
        this.createNotificationListeners(); //add this line
      }

      ////////////////////// Add these methods //////////////////////
  
  //Remove listeners allocated in createNotificationListeners()
componentWillUnmount() {
    this.notificationListener();
    this.notificationOpenedListener();
  }

  async createNotificationListeners() {
    
    this.notificationListener = firebase.notifications().onNotification((notification) => {
        //JSON.stringify(notification);
        const { title, body, data } = notification;
        this.showAlert(data.title, data.body, data.story_detail);
    });
  
    
    this.notificationOpenedListener = firebase.notifications().onNotificationOpened((notificationOpen) => {
        //JSON.stringify(notificationOpen.notification);
        const { title, body, data } = notificationOpen.notification;
        this.showAlert(data.title, data.body, data.story_detail);
    });
  
    
    const notificationOpen = await firebase.notifications().getInitialNotification();
    if (notificationOpen) {
        //JSON.stringify(notificationOpen.notification);
        const { title, body, data } = notificationOpen.notification;
        this.showAlert(data.title, data.body, data.story_detail);
    }

    this.messageListener = firebase.messaging().onMessage((message) => {
      //process data message
      //JSON.stringify(message);
      console.log(JSON.stringify(message));
    });
  }
//   onPress={() =>
//     this.props.navigation.navigate('storyDetail', {
//         item: item,
//     })}
showAlert(title, body, data) {
     //alert(JSON.stringify(data));
    //  this.props.navigation.navigate('storyDetail', {
    //     item: JSON.parse(data),
    // });
    // if(Platform.OS === 'ios'){
    //     NotificationsIOS.setBadgesCount(0);
    // } 
    setTimeout(() => {
    Alert.alert(
      title, body,
      [
          { text: 'OK', onPress: () => this.props.navigation.navigate('storyDetail', {
            item: JSON.parse(data),
        }) },
      ],
      { cancelable: false },
    );
    return false;
}, 3000);
  }
  
    //1
    async checkPermission() {
        const enabled = await firebase.messaging().hasPermission();
        
        if (enabled) {
          
            this.getToken();
        } else {
            this.requestPermission();
        }
      }
      
        //3
      async getToken() {
          //fcmToken = await firebase.messaging().getToken();
            //alert("4 ="+fcmToken);
        let fcmToken = await AsyncStorage.getItem('fcmToken');
        //alert(fcmToken);
        if (!fcmToken) {
            fcmToken = await firebase.messaging().getToken();
            //alert(fcmToken);
            if (fcmToken) {
                // user has a device token
                await AsyncStorage.setItem('fcmToken', fcmToken);
            }
        }
      }
      
        //2
      async requestPermission() {
        try {
            await firebase.messaging().requestPermission();
            // User has authorised
            this.getToken();
        } catch (error) {
            // User has rejected permissions
            console.log('permission rejected');
        }
      }
      
    async componentWillMount() {
        try {
            NetInfo.isConnected.fetch().then(isConnected => {
                console.log('First, is ' + (isConnected ? 'online' : 'offline'));
                //alert('First, is ' + (isConnected ? 'online' : 'offline'));
                if(isConnected===false){
                    alert('Please check your internet connection.');
                    return false;
                }
              });
              function handleFirstConnectivityChange(isConnected) {
                console.log('Then, is ' + (isConnected ? 'online' : 'offline'));
                //alert('Then, is ' + (isConnected ? 'online' : 'offline'));
                
                NetInfo.isConnected.removeEventListener(
                  'connectionChange',
                  handleFirstConnectivityChange
                );
              }
                
            setTimeout(() => {
                // AsyncStorage.multiSet([
                //         ["username", ""],
                //         ["password", ""]
                //     ])
                AsyncStorage.multiGet(['username', 'password']).then((data) => {
                    let username = data[0][1];
                    let password = data[1][1];
                    
                    if (username !== null && username !== ''){
                    //    this.props.navigation.navigate('Profile');
                         this.props.navigation.navigate('Tabs');
                       // this.props.navigation.navigate('GetNoti_Token');
                        return false;
                    }else{
                         this.props.navigation.navigate('Login');
                        //this.props.navigation.navigate('GetNoti_Token');
                        // this.props.navigation.navigate('storyList');
                    }
                        
                });
                
            }, 1000);
        }
        catch (error) {
            console.log('error' + error)
        }
    }

    render() {
        return (
            <View style={styles.container}>
                <ImageBackground source={Images.splash}  resizeMode="stretch" style={styles.container}>

                <View style={styles.logoContainer}>
                               {/* <Image style={styles.logo} 
                                    source={require('../images/logo.png')}>
                                </Image> */}
                            </View>
                </ImageBackground>
            </View>
        );
    }
}
const styles = StyleSheet.create({
    container: { 
        width: '100%',
		height:'100%'
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'center',
        flex: 1
        //top:50
    }
    // logo: {
    //     width: 143,
    //     height: 155, 
    // }
})