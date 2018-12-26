import React, { Component } from 'react';
import {
    StyleSheet,
    View,
    StatusBar, ImageBackground, AsyncStorage, Dimensions, Image, NetInfo
} from 'react-native';
var width = Dimensions.get('window').width; //full width
var height = Dimensions.get('window').height; //full height
import { Images, Colors } from '../themes'
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

export default class Splash extends React.Component {
    static navigationOptions = {
        header: null
    };
    constructor(props) {
        super(props);
 
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
                       this.props.navigation.navigate('Profile');
                        // this.props.navigation.navigate('storyList');
                        return false;
                    }else{
                        this.props.navigation.navigate('Login');
                        // this.props.navigation.navigate('storyList');
                    }
                        
                });
                
            }, 3000);
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