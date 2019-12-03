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
    AlertIOS,Dimensions,ScrollView,I18nManager
} from 'react-native'
import AsyncStorage from '@react-native-community/async-storage';
//I18nManager.forceRTL(true); 
import { userLogin,userInstaLogin } from "../constants/apis";
import Spinner from "../components/Spinner";
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import TimerMixin from 'react-timer-mixin';
import ReactTimeout from 'react-timeout';
import LinearGradient from 'react-native-linear-gradient';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

import DialogInput from 'react-native-dialog-input';

export default class Login extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            username:'',
            password:'',
            // sendInput: '',
            //showDialog: false,
            verificationCode: '',
            isDialogVisible: false
            
        };

          
        // this.state={
        //     visible:this.props.visible
        // };
        this._show=this._show.bind(this);
        this._hide=this._hide.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    }
    showDialog = (value) => {
      // alert(value);
      this.setState({ isDialogVisible: value });
    };
    sendInput = (value) => {
      // alert(value);
      this.setState({ verificationCode: value });
    };
    _show() {
        this.setState({isLoading:true});
        }
        
        _hide(){
        this.setState({isLoading:false});
        }
    openPopup() {
      this.props.navigation.navigate('LoginScreen');
      return false;
      //alert("old apk");
      // this.props.navigation.navigate('OTPVerification');
      // return false;
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
                    ["username", responseData.user_response.username],
                    ["password", this.state.password],
                    ["user_id", responseData.user_id],
                    ["user_notification_status", responseData.user_response.user_notification_status],
                    ["is_profile_updated", responseData.user_response.is_profile_updated]
                    
                    ]);
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
                   //this.props.navigation.navigate('Tabs');
                   if(responseData.user_response.is_profile_updated == 1){
                    this.props.navigation.navigate('Registration');
                   }else{
                    this.props.navigation.navigate('Home');
                    // this.props.navigation.navigate('Tabs');
                   }
                   
                }else{
                    console.log("login false");
                    this.setState({
                        // showPopup: false,
                       // isLoading: false
                        })

                        
                    // alert(responseData.message);
                    if(responseData.message == "challenge_required"){
                      AsyncStorage.multiSet([
                        // ["username", responseData.user_response.username],
                        // ["password", this.state.password],
                        ["challenge_user_id", responseData.user_id],
                        
                        ])
                        this.setState({
                          isLoading: false
                          })
                          setTimeout(() => {
                          this.setState({ showPopup: false });
                        }, 100);
                        this.props.navigation.navigate('OTPVerification');
                      // this.showDialog(true);
                    } else if (responseData.ig_response.two_factor_required == true){
                      // this.showDialog(true);
                      // alert("Two factor enabled!");
                      AsyncStorage.multiSet([
                        // ["username", responseData.user_response.username],
                        // ["password", this.state.password],
                        ["challenge_user_id", responseData.user_id],
                        
                        ])
                        this.setState({
                          isLoading: false
                          })
                          setTimeout(() => {
                          this.setState({ showPopup: false });
                        }, 100);
                        this.props.navigation.navigate('twoFactorOTPVerification');
                      
                    } else {
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
                    }
                   
                    
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
                this.handleBackPress
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
          this.handleBackPress
        );
      }
      handleBackButtonClick() {
        this.props.navigation.goBack(null);
        return true;
      }
      handleBackPress = () => {
        BackHandler.exitApp(); // works best when the goBack is async
        return true;
      };
    // componentDidMount = () => this.closeActivityIndicator()
    logout() {
      Cookie.clear().then(() => {
        this.setState({ token: null })
      })
    }

    async instaLogin(token){
      console.log(token);
      this.setState({ token });

      this.setState({ isLoading: true });
      let fcmToken = await AsyncStorage.getItem('fcmToken');
      // alert(fcmToken);
      // return false;
      let formBody="access_token="+this.state.token+"";
      // setTimeout(() => {
      //     this.setState({ isLoading: false });
      //   }, 15000);
     fetch(
          // "http://192.168.1.23/10k-club/webservices/first.php/",
      //    "http://10k.tempurl.co.il/webservices/first.php/",
      userInstaLogin,
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
                  ["username", responseData.user_response.username],
                  ["password", ''],
                  ["user_id", responseData.user_id],
                  ["user_notification_status", responseData.user_response.user_notification_status],
                  ["is_profile_updated", responseData.user_response.is_profile_updated],
                  ["access_token", this.state.token]
                  
                  ]);
                  console.log("login true");
                  
                  // setTimeout(() => {
                  //     this.setState({ showPopup: false });
                  //         this.setState({ isLoading: false });
                          
                  //       }, 15000);
                  //this.closeActivityIndicator();
                  this.setState({
                      isLoading: false
                      })
                    //   setTimeout(() => {
                    //   this.setState({ showPopup: false });
                    // }, 100);
                   // this.setState({ showPopup: false });
                 // this.props.navigation.navigate('Profile');
                 //this.props.navigation.navigate('Tabs');
                 if(responseData.user_response.is_profile_updated == 1){
                  this.props.navigation.navigate('Registration');
                 }else{
                  this.props.navigation.navigate('Home');
                  // this.props.navigation.navigate('Tabs');
                 }
                 
              }else{
                  console.log("login false");                 
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
    }
    render() {
        return (
            <ImageBackground source={Images.screen_1_back}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
            
            {/* <View>
    <TouchableOpacity onPress={() => this.refs.ins.show()}>
        <Text style={{color: 'white'}}>Login</Text>
    </TouchableOpacity>
    <InstagramLogin
        ref= {'ins'}
        clientId='b6dd72374255453e8be0016e9c266d17'
 	      redirectUrl='http://10k-club.com'
        scopes={['public_content', 'follower_list']}
        onLoginSuccess={(token) => this.setState({ token })}
        onLoginFailure={(data) => console.log(data)}
    />
</View> */}
                <StatusBar barStyle="light-content" />
                {/* <KeyboardAvoidingView behavior='padding' style={styles.container}> */}
                    {/* <TouchableWithoutFeedback style={styles.container}  */}
                            {/* onPress={() => this.setState({ showPopup: false })}> */}
                            
                        
                        <View style={styles.logoContainer}>
						
                            <View style={styles.logoContainer}>
                            

                               <Image style={styles.logo}
                                    // source={require('../images/logo.png')}> 
                                    source={require('../images/logo_login.png')}> 
                                </Image>
                                
                            </View>
                            
                            </View>
                            
                            {/* {
						this.state.isLoading &&
						<Spinner />
          }  */}
          
                            
                            <View style={styles.loginButtonContainer}>
                            <Text style={styles.text_login}> LOG IN BY</Text>
                            {/* this.refs.ins.show() */}
                            <TouchableOpacity 
                            onPress={() => this.openPopup()}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#f6f6f6', '#d7d7d7', '#b3b3b3']}style={styles.loginButton}>
                            
                            
                            <Image style={styles.ista_logo} 
                                    // source={require('../images/logo.png')}> 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image>
                                    <Text style={styles.buttonText}>
                                    {/* Login with instagram */}
                                    {/* כניסה דרך אינסטגרם */}
                                    Instagram
                                    </Text>
                                   
                                
                                </LinearGradient>
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
                            height: 250, width: '100%' //, backgroundColor: '#2C2C2C'
                        }}>
                         <LinearGradient start={{x: 0, y: 0}} end={{x: 0, y: 1}} colors={['#545454', '#232323', '#040404']} >
                        <TouchableOpacity onPress={() => this.setState({ showPopup: false })}>
                            <Image source={Images.close}
                                style={{ width: 25, height: 25, marginTop: 20, marginLeft: 10 }} />
                        </TouchableOpacity>
                        <Text style={styles.headerTitle}>
                        {/* Login With Instagram */}
                        LOG IN WITH INSTAGRAM
                        {/* כניסה דרך אינסטגרם */}
                        </Text> 
                                <View 
                                    style={{
                                        height: '100%', width: '100%',
                                        justifyContent: 'center', alignItems: 'center', backgroundColor: 'transparent'
                                    }}>
                                    
                                    {/* <ImageBackground source={require('../images/logo.png')}
                                        resizeMode={"cover"} style={{ height: 250, width: "100%" }} /> */}

                                        <View style={styles.infoContainer}>
                                        <Image style={styles.instagram_login_screen_img}
                                // source={require('../images/logo.png')}> 
                                source={require('../images/10k-Instagram.png')}> 
                            </Image>
                                <TextInput style={styles.input}
                                    // placeholder="תעודת זהות"
                                    placeholder="USERNAME"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#99b0bc'
                                    keyboardType='email-address'
                                    returnKeyType='next'
                                    autoCorrect={false}
                                    onSubmitEditing={()=> this.refs.txtPassword.focus()}
                                    onChangeText={(text) => this.setState({username:text})}
                                />
                                <TextInput style={styles.input} 
                                    // placeholder="סיסמה"
                                    placeholder="PASSWORD"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#99b0bc'
                                    returnKeyType='go'
                                    secureTextEntry
                                    autoCorrect={false}
                                    ref={"txtPassword"}
                                    onChangeText={(text) => this.setState({password:text})}
                                />
                                <TouchableOpacity onPress={()=> this.login()}  borderColor="transparent" style={{alignItems:'center'}}>
                                <LinearGradient start={{x: 0, y: 0}} end={{x: 0, y: 1}} colors={['#2e2f2f', '#171718', '#050505']} style={styles.buttonContainer}>
                                    <Text style={{
        textAlign: 'center',
        color :'#fff',
        fontWeight: 'bold',
        fontSize: 18,
        paddingHorizontal:10,
        // fontFamily: "Billabong",
	}}>
  
                                    SIGN IN
                                    
                                    {/* היכנס */}
                                    </Text>
                                    </LinearGradient>
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
                                </LinearGradient>
                    </View>
                    </TouchableWithoutFeedback>
                </KeyboardAvoidingView>
                {/* </KeyboardAwareScrollView> */}
                </TouchableOpacity>
                
                {/* </KeyboardAwareScrollView> */}
                </Modal>
                <DialogInput isDialogVisible={this.state.isDialogVisible}
            title={"OTP Verification"}
            message={"Please enter OTP that you have received on email or phone."}
            hintInput ={"OTP"}
            submitInput={ (inputText) => {this.sendInput(inputText)} }
            closeDialog={ () => {this.showDialog(false)}}>
</DialogInput>

<View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
        {/* {!this.state.token ? (
          <TouchableOpacity style={{ borderRadius: 5, backgroundColor: 'orange', height: 30, width: 100, justifyContent: 'center', alignItems: 'center' }} onPress={() => this.refs.ins.show()}>
            <Text style={{ color: 'white' }}>Login</Text>
          </TouchableOpacity>
        ) : (
            <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
              <Text style={{ margin: 10 }}>token: {this.state.token}</Text>
              <TouchableOpacity style={{ borderRadius: 5, backgroundColor: 'green', height: 30, width: 100, justifyContent: 'center', alignItems: 'center' }} onPress={() => this.logout()}>
                <Text style={{ color: 'white' }}>Logout</Text>
              </TouchableOpacity>
            </View>
          )
        }
        {this.state.failure && <View>
          <Text style={{ margin: 10 }}>failure: {JSON.stringify(this.state.failure)}</Text>
        </View>} */}
        <KeyboardAvoidingView behavior='padding' style={styles.container}>
        <InstagramLogin
          ref='ins'
          clientId='b6dd72374255453e8be0016e9c266d17'
          redirectUrl='http://10k-club.com'
          scopes={['public_content+follower_list']}
          onLoginSuccess={(token) => this.instaLogin(token)}
          onLoginFailure={(data) => this.setState({ failure: data })}
        />
        </KeyboardAvoidingView>
      </View>
                
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
        top:60
    },
    logo: {
        width: 143,
        height: 155,
    },

    ista_logo: {
        width: 25,
        height: 25,
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
        top: 0,
        height: 200,
        padding: 20,
    },
    input: {
        height: 40,
        // backgroundColor: 'rgba(255,255,255,0.2)',
        backgroundColor: 'transparent',
        color: '#FFF',
        marginBottom: 20,
        paddingHorizontal: 10,
        textAlign: 'left',
        borderColor: '#99b0bc',
        borderWidth: 0.5
    },
    buttonContainer: {
        // flex:1,
        backgroundColor: '#f7c744',
        paddingVertical: 20,
        width:'60%',
        
    },
    buttonText: {
        textAlign: 'center',
        color :'#000000',
        fontWeight: 'bold',
        fontSize: 18,
        paddingHorizontal:10,
        fontFamily: "Billabong",
	},
	closeStyle: {
		marginTop: 22,
      },
      loginButtonContainer:{
        position: 'absolute',
        left: 0,
        right: 0,
        bottom: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 80 : 100) : 30,
        height: 150,
        padding: 15,
        marginHorizontal:30,
        // alignItems:'flex-end',
        justifyContent: 'flex-end',
        zIndex:1
        // flex:1,
      },
      loginButton: {
        //backgroundColor: 'transparent',
        paddingVertical: 15, 
        borderRadius:100,
    borderWidth: 1,
    borderColor: '#fff',
        flexDirection:'row',
        alignItems: 'center',
         
        justifyContent:'center'
  
      },

      loginButton_liner: {
        //backgroundColor: 'transparent',
    //     paddingVertical: 15, 
    //     borderRadius:100,
    // borderWidth: 1,
    // borderColor: '#fff',
        flexDirection:'row',
        //alignItems: 'center',
         
        //justifyContent:'center'
  
      },
      headerTitle: {
        // position: 'absolute',
        // marginLeft: 100,
        // fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
        color: Colors.colorWhite,
        fontWeight: "bold",
        // top: 50,
        padding: 15, 
        // flex: 1,
        // bottom:50,
        justifyContent:"center",
        alignItems: "center",
        fontSize: 20,
        textAlign:'center',
        // marginBottom:20
      },
      text_login:{
        textAlign: 'center',
        color :'#ffffff',
        fontSize: 20,
        fontFamily:'Montserrat-SemiBold',
        marginBottom: 10 
      },
      instagram_login_screen_img: {
        position:'absolute',
        top: Platform.OS === "ios" ? -5 : 2,
        right:0,
        width: Platform.OS === "ios" ? 92 : 82,
        height: Platform.OS === "ios" ? 150 : 135,
        zIndex: 1,
        marginTop:0

    }
})