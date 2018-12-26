/*
Login Screen
*/
import React, { Component } from 'react'
import InstagramLogin from 'react-native-instagram-login'
import Cookie from 'react-native-cookie'

import { Images, Colors } from '../themes'
import Spinner from "../components/Spinner";
import {
    Platform,
    StyleSheet, Text, View, Image,
    TouchableWithoutFeedback, StatusBar,
    TextInput, SafeAreaView, Keyboard, TouchableOpacity,
    KeyboardAvoidingView,ImageBackground,Modal,Alert,TouchableHighlight,BackHandler,ToastAndroid,
    AlertIOS,Dimensions,AsyncStorage
} from 'react-native'
import { userLogin } from "../constants/apis";
var width = Dimensions.get('window').width; //full width
var height = Dimensions.get('window').height; //full height
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

export default class Login extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: true, 
            showPopup: false,
            username:'',
            password:''
        };

    }
    openPopup() {

        this.setState({
           showPopup: true
       })
    }
    
	login() {
        
        this.setState({
            isLoading: true
       })
        
        if (this.state.username == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please enter username");
              },
              android: () => {
                ToastAndroid.show("Please enter username", ToastAndroid.SHORT);
              }
            })();
            return false;
          }
          if (this.state.password == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please enter password");
              },
              android: () => {
                ToastAndroid.show("Please enter password", ToastAndroid.SHORT);
              }
            })();
            return false;
          }
        
        let formBody="username="+this.state.username+"&password="+this.state.password+"";
        
        fetch(
            // "http://192.168.1.23/10k-club/webservices/first.php/",
        //    "http://10k.tempurl.co.il/webservices/first.php/",
        userLogin,
            {
                method: 'POST',
                headers: {
                    Authorization: "Bearer token",
                    "Content-Type": "application/x-www-form-urlencoded"
                  },
                  body: formBody,
            }
          )
            .then(response => response.json())
            .then(responseData => {
                // alert(JSON.stringify(responseData));
              console.log(responseData);
            
              if(responseData.status == 'true'){
                    AsyncStorage.multiSet([
                    ["username", this.state.username],
                    ["password", this.state.password],
                    ["user_id", responseData.user_id]
                    ])
                    // console.log(error);
                    this.setState({
                    showPopup: false,
                    isLoading: false
                    })
                    this.props.navigation.navigate('Profile');
                }else{
                    // alert(responseData.message);
                    Platform.select({
                        ios: () => {
                          AlertIOS.alert(responseData.message);
                        },
                        android: () => {
                          ToastAndroid.show(responseData.message, ToastAndroid.SHORT);
                        }
                      })();
                      return false;

                }
            })
            .catch(error => {
                alert(error);
              //this.setState({ isLoading: false });
             // this.state.username = 'test';
                
            });
      }
      
      async componentWillMount() {
        try {
            
                // AsyncStorage.multiSet([
                //         ["username", ""],
                //         ["password", ""]
                //     ])
                // AsyncStorage.multiGet(['username', 'password']).then((data) => {
                //     let username = data[0][1];
                //     let password = data[1][1];
                    
                //     if (username !== null && username !== ''){
                //         //Your logic
                //         this.props.navigation.navigate('Profile');
                //         return false;
                //     }else{
                //         this.props.navigation.navigate('Login');
                //     }
                        
                // });
                
            
        }
        catch (error) {
            console.log('error' + error)
        }
    }
    render() {
        return (
            <ImageBackground source={Images.screen_1}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
                
                <StatusBar barStyle="light-content" />
                <KeyboardAvoidingView behavior='padding' style={styles.container}>
                    <TouchableWithoutFeedback style={styles.container} 
                            onPress={Keyboard.dismiss}>
                        <View style={styles.logoContainer}>
						
                            <View style={styles.logoContainer}>
                            

                               <Image style={styles.logo}
                                    source={require('../images/logo.png')}> 

                                </Image>
                                
                            </View>
                            <View style={styles.loginButtonContainer}>
                            <TouchableOpacity onPress={() => this.openPopup()} style={styles.loginButton}>
                                    <Text style={styles.buttonText}>Login with instagram</Text>
                                </TouchableOpacity>
                            </View> 
                            
                    <Modal
                    transparent={true}
                    supportedOrientations={['portrait', 'landscape']}
                    visible={this.state.showPopup}
                    onRequestClose={() => console.log('')}>
                    <KeyboardAvoidingView behavior='padding' style={styles.container}>
                    <TouchableWithoutFeedback style={styles.container} 
                            onPress={Keyboard.dismiss}>
                    <View
                        style={{ flex:2,
                        justifyContent:'center',
                        top:150,
                        bottom:150,
                            height: 400, width: '100%', backgroundColor: '#2C2C2C'
                        }}>
                        <TouchableOpacity onPress={() => this.setState({ showPopup: false })}>
                            <Image source={Images.close}
                                style={{ width: 25, height: 25, marginTop: 40, marginLeft: 10 }} />
                        </TouchableOpacity>
                        <Text style={styles.headerTitle}>Login With Instagram</Text> 
                                <View 
                                    style={{
                                        height: '100%', width: '100%',
                                        justifyContent: 'center', alignItems: 'center', backgroundColor: 'transparent'
                                    }}>
                                    
                                    {/* <ImageBackground source={require('../images/logo.png')}
                                        resizeMode={"cover"} style={{ height: 250, width: "100%" }} /> */}

                                        <View style={styles.infoContainer}>
                                <TextInput style={styles.input}
                                    placeholder="Username"
                                    placeholderTextColor='rgba(255,255,255,0.8)'
                                    keyboardType='email-address'
                                    returnKeyType='next'
                                    autoCorrect={false}
                                    onSubmitEditing={()=> this.refs.txtPassword.focus()}
                                    onChangeText={(text) => this.setState({username:text})}
                                />
                                <TextInput style={styles.input} 
                                    placeholder="Password"
                                    placeholderTextColor='rgba(255,255,255,0.8)'
                                    returnKeyType='go'
                                    secureTextEntry
                                    autoCorrect={false}
                                    ref={"txtPassword"}
                                    onChangeText={(text) => this.setState({password:text})}
                                />
                                <TouchableOpacity onPress={()=> this.login()} style={styles.buttonContainer}>
                                    <Text style={styles.buttonText}>SIGN IN</Text>
                                </TouchableOpacity>
                            </View>
                                </View>
                        
                    </View>
                    </TouchableWithoutFeedback>
                </KeyboardAvoidingView>
                </Modal>
                            
                        </View>
                    </TouchableWithoutFeedback>
                </KeyboardAvoidingView>
                
            </SafeAreaView>
            </ImageBackground>
        )
    }
}
const styles = StyleSheet.create({
    container: { 
        width: '100%',
		height:'100%'
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'flex-start',
        flex: 1,
        top:30
    },
    logo: {
        width: 143,
        height: 155,
    },
    title: {
        color: '#f7c744',
        fontSize: 18,
        textAlign: 'center',
        marginTop: 5,
        opacity: 0.9
    },
    infoContainer: {
        position: 'absolute',
        left: 0,
        right: 0,
        top: 50,
        height: 200,
        padding: 20,
    },
    input: {
        height: 40,
        backgroundColor: 'rgba(255,255,255,0.2)',
        color: '#FFF',
        marginBottom: 20,
        paddingHorizontal: 10
    },
    buttonContainer: {
        backgroundColor: '#f7c744',
        paddingVertical: 15
    },
    buttonText: {
        textAlign: 'center',
        color :'#ffffff',
        fontWeight: 'bold',
        fontSize: 18
	},
	closeStyle: {
		marginTop: 22,
      },
      loginButtonContainer:{
        position: 'absolute',
        left: 0,
        right: 0,
        bottom: 230,
        height: 200,
        padding: 15,
        //alignItems:'flex-start',
        justifyContent: 'flex-start',
        flex:1,
      },
      loginButton: {
        backgroundColor: '#1db954',
        paddingVertical: 15,
        borderRadius:100
      },
      headerTitle: {
        position: 'absolute',
        marginLeft: 100,
        // fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
        color: Colors.colorWhite,
        fontWeight: "bold",
        top: 50,
        padding: 15, 
        flex: 1,
        // bottom:50,
        justifyContent:"center",
        alignItems: "center"
      }
})