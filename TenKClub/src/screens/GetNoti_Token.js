import React, {Component} from 'react';
import { AsyncStorage,View,Text } from 'react-native';
import firebase from 'react-native-firebase';


export default class GetNoti_Token extends Component{

    async componentDidMount() {
      this.checkPermission();
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
      alert(fcmToken);
      if (!fcmToken) {
          fcmToken = await firebase.messaging().getToken();
          alert("4");
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
    
      render() {
        return (
          <View style={{flex: 1}}>
            <Text>Welcome to React Native!</Text>
          </View>
        );
      }
    }
    