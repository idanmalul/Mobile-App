/*
Login Screen
*/
import React, { Component } from 'react'
import InstagramLogin from 'react-native-instagram-login'
import Cookie from 'react-native-cookie'
import firebase from 'react-native-firebase';
import { Images, Colors } from '../themes'
import _ from "lodash";
import {
    Platform,
    StyleSheet, Text, View, Image,
    TouchableWithoutFeedback, StatusBar,
    TextInput, SafeAreaView, Keyboard, TouchableOpacity,
    KeyboardAvoidingView,ImageBackground,Modal,Alert,TouchableHighlight,BackHandler,ToastAndroid,
    AlertIOS,Dimensions,AsyncStorage,ScrollView,I18nManager
} from 'react-native'
I18nManager.forceRTL(true); 
import { userLogin } from "../constants/apis";
import Spinner from "../components/Spinner";
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import TimerMixin from 'react-timer-mixin';
import ReactTimeout from 'react-timeout';

var width = Dimensions.get('window').width; //full width
var height = Dimensions.get('window').height; //full height
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

export default class Login extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            username:'',
            password:''
        };

          
        // this.state={
        //     visible:this.props.visible
        // };
        this._show=this._show.bind(this);
        this._hide=this._hide.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    }
    _show() {
        this.setState({isLoading:true});
        }
        
        _hide(){
        this.setState({isLoading:false});
        }
    openPopup() {
        this.setState({
           showPopup: true
       })
    }
    /*
    async componentDidMount() {
        this.checkPermission();
        this.createNotificationListeners(); //add this line
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
            alert(fcmToken);
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
      }  */
	async login() {
        
        
        
          
    //    return false;
        
        if (this.state.username == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please enter username");
              },
              android: () => {
                // ToastAndroid.show("Please enter username", ToastAndroid.LONG, ToastAndroid.CENTER);
                ToastAndroid.showWithGravityAndOffset(
                    "Please enter username",
                    ToastAndroid.LONG,
                    ToastAndroid.BOTTOM,
                    55,
                    80,({
                        style: {
                            backgroundColor: "blue"
                           }
                    }

                    )
                    
                  );
                        
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
                ToastAndroid.show("Please enter password", ToastAndroid.LONG, ToastAndroid.BOTTOM);
              }
            })();
            return false;
          }
         this.setState({ isLoading: true });
        let fcmToken = await AsyncStorage.getItem('fcmToken');
        // alert(fcmToken);
        // return false;
        let formBody="username="+this.state.username+"&password="+this.state.password+"&gcm_id="+fcmToken+"&device_name="+""+"";
        // setTimeout(() => {
        //     this.setState({ isLoading: false });
        //   }, 15000);
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
                    console.log("login true");
                    
                    // setTimeout(() => {
                    //     this.setState({ showPopup: false });
                    //         this.setState({ isLoading: false });
                            
                    //       }, 15000);
                    //this.closeActivityIndicator();
                    this.setState({
                        isLoading: false
                        })
                        setTimeout(() => {
                        this.setState({ showPopup: false });
                      }, 100);
                     // this.setState({ showPopup: false });
                   // this.props.navigation.navigate('Profile');
                   this.props.navigation.navigate('Tabs');
                }else{
                    console.log("login false");
                    this.setState({
                        // showPopup: false,
                       // isLoading: false
                        })
                    // alert(responseData.message);
                   setTimeout(() => {
                        Alert.alert(
                            'Alert',
                            responseData.message,
                            [
                             // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
                            //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
                              {text: 'OK', onPress: () => this.setState({ isLoading: false })},
                            ],
                            { cancelable: false }
                          )
                     }, 100);
                    
                 /*   Platform.select({
                        ios: () => {
                          AlertIOS.alert(alert_res);
                        },
                        android: () => {
                          ToastAndroid.show(alert_res, ToastAndroid.LONG, ToastAndroid.CENTER);
                        }
                      })();
                     */
                    //   return false;

                }
            })
            .catch(error => {
                console.log("login catch");
                //alert(error);
              //this.setState({ isLoading: false });
             setTimeout(() => {
                Alert.alert(
                    'Alert',
                    error,
                    [
                     // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
                    //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
                      {text: 'OK', onPress: () => this.setState({ isLoading: false })},
                    ],
                    { cancelable: false }
                  )
             }, 100);
             // this.state.username = 'test';
                
            });
            // setTimeout(() => {
            //     this.setState({ isLoading: false });
            // }, 10000);
            
      }

    // closeActivityIndicator = () => setTimeout(() => this.setState({
    //     isLoading: false,showPopup: false }), 10000)

      async componentWillMount() {
        try {
            BackHandler.addEventListener(
                "hardwareBackPress",
                this.handleBackButtonClick
              );
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

    componentWillUnmount() {
        
        BackHandler.removeEventListener(
          "hardwareBackPress",
          this.handleBackButtonClick
        );
      }
      handleBackButtonClick() {
        this.props.navigation.goBack(null);
        return true;
      }
    // componentDidMount = () => this.closeActivityIndicator()
    render() {
        return (
            <ImageBackground source={Images.screen_1}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
                
                <StatusBar barStyle="light-content" />
                {/* <KeyboardAvoidingView behavior='padding' style={styles.container}> */}
                    {/* <TouchableWithoutFeedback style={styles.container}  */}
                            {/* onPress={() => this.setState({ showPopup: false })}> */}
                            
                        
                        <View style={styles.logoContainer}>
						
                            <View style={styles.logoContainer}>
                            

                               <Image style={styles.logo}
                                    source={require('../images/logo.png')}> 

                                </Image>
                                
                            </View>
                            </View>
                            {/* {
						this.state.isLoading &&
						<Spinner />
          }  */}
                            
                            <View style={styles.loginButtonContainer}>
                            <TouchableOpacity onPress={() => this.openPopup()} style={styles.loginButton}>
                                    <Text style={styles.buttonText}>
                                    {/* Login with instagram */}
                                    כניסה דרך אינסטגרם
                                    </Text>
                                </TouchableOpacity>
                            </View> 
                            
                            
                    {/* </TouchableWithoutFeedback> */}
                {/* </KeyboardAvoidingView> */}
                
                
                    <Modal
                    animationType={"slide"}
                    transparent={true}
                    // closeOnClick={true}
                    supportedOrientations={['portrait', 'landscape']}
                    visible={this.state.showPopup}
                    // onTouchOutside={() => {
                    //     this.setState({ showPopup: false });
                    //   }}
                    //   onBackdropPress={() => {
                    //     this.setState({ showPopup: false });
                    //   }}
                    onRequestClose={() => {this.setState({ showPopup: false })}}
                    //onRequestClose={() => console.log('')}>
                    >
                    {/* <KeyboardAwareScrollView> */}
                    <TouchableOpacity 
            style={styles.container} 
            activeOpacity={1} 
            onPressOut={() => {this.setState({ showPopup: false })}}
          >
          
                    <KeyboardAvoidingView behavior='padding' style={styles.container}>
                    <TouchableWithoutFeedback style={styles.container} 
                            onPress={Keyboard.dismiss}>
                    <View
                        style={{ //flex:2,
                        justifyContent:'center',
                        top:100,
                        bottom:150,
                            height: 400, width: '100%', backgroundColor: '#2C2C2C'
                        }}>
                        <TouchableOpacity onPress={() => this.setState({ showPopup: false })}>
                            <Image source={Images.close}
                                style={{ width: 25, height: 25, marginTop: 40, marginLeft: 10 }} />
                        </TouchableOpacity>
                        <Text style={styles.headerTitle}>
                        {/* Login With Instagram */}
                        כניסה דרך אינסטגרם
                        </Text> 
                                <View 
                                    style={{
                                        height: '100%', width: '100%',
                                        justifyContent: 'center', alignItems: 'center', backgroundColor: 'transparent'
                                    }}>
                                    
                                    {/* <ImageBackground source={require('../images/logo.png')}
                                        resizeMode={"cover"} style={{ height: 250, width: "100%" }} /> */}

                                        <View style={styles.infoContainer}>
                                <TextInput style={styles.input}
                                    placeholder="תעודת זהות"
                                    placeholderTextColor='rgba(255,255,255,0.8)'
                                    keyboardType='email-address'
                                    returnKeyType='next'
                                    autoCorrect={false}
                                    onSubmitEditing={()=> this.refs.txtPassword.focus()}
                                    onChangeText={(text) => this.setState({username:text})}
                                />
                                <TextInput style={styles.input} 
                                    placeholder="סיסמה"
                                    placeholderTextColor='rgba(255,255,255,0.8)'
                                    returnKeyType='go'
                                    secureTextEntry
                                    autoCorrect={false}
                                    ref={"txtPassword"}
                                    onChangeText={(text) => this.setState({password:text})}
                                />
                                <TouchableOpacity onPress={()=> this.login()} style={styles.buttonContainer}>
                                    <Text style={styles.buttonText}>
                                    {/* SIGN IN */}
                                    היכנס
                                    </Text>
                                </TouchableOpacity>
                            </View>
                            {
						this.state.isLoading &&
						<Spinner />
                         } 
                         {
						!this.state.isLoading &&
						 null
                         } 
                            
                                </View>
                        
                    </View>
                    </TouchableWithoutFeedback>
                </KeyboardAvoidingView>
                {/* </KeyboardAwareScrollView> */}
                </TouchableOpacity>
                {/* </KeyboardAwareScrollView> */}
                </Modal>
                
            </SafeAreaView>
            
            </ImageBackground>
        )
    }
}
const styles = StyleSheet.create({
    container: { 
        flex:1,
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
        paddingHorizontal: 10,
        textAlign:'right' 
    },
    buttonContainer: {
        // flex:1,
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
        bottom: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 80 : 100) : 180,
        height: 200,
        padding: 15,
        //alignItems:'flex-end',
        justifyContent: 'flex-end',
        // flex:1,
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