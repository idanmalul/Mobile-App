/*
Forgot Screen Design
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
    AlertIOS,Dimensions,ScrollView,I18nManager, Linking,
} from 'react-native'
import AsyncStorage from '@react-native-community/async-storage';
//I18nManager.forceRTL(true); 
import { UserForgotPass } from "../constants/apis";
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

export default class ForgotPass extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            username:'',
            email:'',
            password:'',
            // sendInput: '',
            //showDialog: false,
            verificationCode: '',
            isDialogVisible: false,
            visible: false
            
        };

          
        // this.state={
        //     visible:this.props.visible
        // };
        this._show=this._show.bind(this);
        this._hide=this._hide.bind(this);
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    }
    closeDialogBox(){
      this.setState({ visible: false })
      this.props.navigation.navigate('Login');
      
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
      //alert("old apk");
      // this.props.navigation.navigate('OTPVerification');
      // return false;
        this.setState({
           showPopup: true
       })
       
    }
    
      async user_forgotPass() {
        
        
        //    return false;
            
            if (this.state.email == "") {
                Platform.select({
                  ios: () => {
                    AlertIOS.alert("Please enter email");
                  },
                  android: () => {
                    // ToastAndroid.show("Please enter username", ToastAndroid.LONG, ToastAndroid.CENTER);
                    ToastAndroid.showWithGravityAndOffset(
                        "Please enter email",
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
              // if (this.state.password == "") {
              //   Platform.select({
              //     ios: () => {
              //       AlertIOS.alert("Please enter password");
              //     },
              //     android: () => {
              //       ToastAndroid.show("Please enter password", ToastAndroid.LONG, ToastAndroid.BOTTOM);
              //     }
              //   })();
              //   return false;
              // }
             this.setState({ isLoading: true });
           // let fcmToken = await AsyncStorage.getItem('fcmToken');
            // alert(fcmToken);
            // return false;
            let formBody="email="+this.state.email+"";
            // setTimeout(() => {
            //     this.setState({ isLoading: false });
            //   }, 15000);
           fetch(
                // "http://192.168.1.23/10k-club/webservices/first.php/",
            //    "http://10k.tempurl.co.il/webservices/first.php/",
            UserForgotPass, 
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
                  // alert("Yes");
                  if(responseData.status == 'true'){
                   
                        // AsyncStorage.multiSet([
                        // ["username", responseData.user_response.username],
                        // ["password", this.state.password],
                        // ["user_id", responseData.user_id],
                        // ["user_notification_status", responseData.user_response.user_notification_status],
                        // ["is_profile_updated", responseData.user_response.is_profile_updated],
                        // ["member_username", responseData.user_response.member_username],
                        // ]);
                        console.log("forgot true");
                        setTimeout(() => {
                          Alert.alert(
                              'Alert',
                              responseData.message,
                              [
                               // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
                              //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
                                {text: 'OK', onPress: () => this.onPressAlertOk() },
                              ],
                              { cancelable: false }
                            )
                       }, 100);
                        
                        // setTimeout(() => {
                        //     this.setState({ showPopup: false });
                        //         this.setState({ isLoading: false });
                                
                        //       }, 15000);
                        //this.closeActivityIndicator();
                        // this.setState({
                        //     isLoading: false
                        //     });
                        //     setTimeout(() => {
                        //     // this.setState({ showPopup: false });
                        //   }, 100);
                          // console.log("777777777 login true");
                         // this.setState({ showPopup: false });
                       // this.props.navigation.navigate('Profile');
                       //this.props.navigation.navigate('Tabs');
                      //  if(responseData.user_response.is_profile_updated == 1){
                      //   this.props.navigation.navigate('Registration');
                      //  }else{
                      //   this.props.navigation.navigate('Home');
                      //   console.log("888888888 login true");
                      //   // this.props.navigation.navigate('Tabs');
                      //  }
                       
                    }else{
                        console.log("login false");
                        
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

          onPressAlertOk(){
            this.setState({ isLoading: false });
            this.props.navigation.navigate('LoginScreen');
          }

      how_it_works(){
        this.props.navigation.navigate('GuidelineScreen_1');
        return false; 
      }
    // closeActivityIndicator = () => setTimeout(() => this.setState({
    //     isLoading: false,showPopup: false }), 10000)

      async componentWillMount() {
        // this.openPopup();
        // this.showPrivacyPolicy();
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
    render() {
      
        return (
          <View style={styles.container}>

             {/* <ImageBackground source={Images.screen_1_back}  resizeMode="stretch" style={styles.container}> */} 
             
             <SafeAreaView style={styles.container}>
            
                
            <StatusBar barStyle="light-content"/>
            {/* <KeyboardAvoidingView behavior='padding' style={styles.container}> */}
                {/* <TouchableWithoutFeedback style={styles.container}  */}
                        {/* onPress={() => this.setState({ showPopup: false })}> */}
                        <View>
                <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.setting_back} style={styles.headerIcon} />
                    </TouchableOpacity> 
                    </View>
                        <KeyboardAwareScrollView >  
                    <View style={styles.logoContainer}>
        
                        <View style={styles.logoContainer}>
                        

                           <Image style={styles.logo}
                                // source={require('../images/logo.png')}> 
                                source={require('../images/logo-min.png')}> 
                            </Image>
                            <Text style={styles.buttonText}>
                                {/* Login with instagram */}
                                {/* כניסה דרך אינסטגרם */}
                                FORGOT PASSWORD
                                </Text>
                        </View>
                        
                        </View>

                        <View style={styles.profileImage}>
                {/* <Image source={require('../images/user-min.png')}
          style={{width: 100, height: 100, borderRadius: 100/2}} /> */}
          
                </View>

                {/* <View style={styles.loginButtonSection}>
 <TouchableOpacity  
         style={styles.loginButton} onPress={()=> this.onContinueOption()}>
         <Text style={{ color: 'white',padding:10}}>Continue as {this.state.userID}</Text>
         </TouchableOpacity>
       </View>

       <View style={{flex:1,flexDirection:'row',marginTop:10, justifyContent: 'center'}}>
       <Text style={{ color: 'white',paddingTop:10}}>Not {this.state.userID}?</Text>
       
                                <View>
                                <TouchableOpacity onPress={()=> this.switcaccount()}>
       <Text style={{ color: '#0078D6',paddingTop:10}}> Switch account
       </Text>
       </TouchableOpacity>
       </View>
       
       </View> */}

<View style={styles.infoContainer}>
                                        {/* <Image style={styles.instagram_login_screen_img}
                                
                                source={require('../images/10k-Instagram.png')}> 
                            </Image> */}
                                <TextInput style={styles.input}
                                    // placeholder="תעודת זהות"
                                    placeholder="Email"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#A7B8B7'
                                    keyboardType='email-address'
                                    returnKeyType='next'
                                    autoCorrect={false}
                                    onSubmitEditing={()=> this.refs.txtPassword.focus()}
                                    onChangeText={(text) => this.setState({email:text})} 
                                />
                                {/* <TextInput style={styles.input} 
                                    // placeholder="סיסמה"
                                    placeholder="Password"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#A7B8B7'
                                    returnKeyType='go'
                                    secureTextEntry
                                    autoCorrect={false}
                                    ref={"txtPassword"}
                                    onChangeText={(text) => this.setState({password:text})}
                                /> */}


                                <TouchableOpacity onPress={()=> this.user_forgotPass()}  borderColor="transparent" style={{alignItems:'center'}}>
                                <LinearGradient start={{x: 0, y: 0}} end={{x: 0, y: 1}} colors={['#070909', '#070909', '#070909']} style={styles.buttonContainer}>
                                    <Text style={{
        textAlign: 'center',
        color :'#f2f2f3',
        // fontWeight: 'bold',
        fontSize: 19,
        // paddingHorizontal:10,
        // fontFamily: "Billabong",
	}}>
  
                                    Forgot
                                    
                                    {/* היכנס */}
                                    </Text>
                                    </LinearGradient>
                                </TouchableOpacity>
                            </View>

                        
                    
               
             </KeyboardAwareScrollView>
            
        </SafeAreaView>
            {/* <KeyboardAvoidingView> */}
        <View  style={{
                      //  justifyContent: 'center', 
                      //  alignItems: 'flex-end',
                      
                            paddingHorizontal: 40, 
                            // marginBottom:20,
                            // alignItems:'flex-end',  
                            // justifyContent:'flex-end',
                          // borderBottomColor: '#DDDDDD',
                          // borderBottomWidth: 1,
                          }}>
                          <View
                          style={{
                            // position: 'absolute', left: 0, right: 0, bottom: 50,
                            // zIndex:1,
                            // position: 'relative',
                       bottom: 50,
                            // paddingHorizontal: 40, 
                            // marginBottom:20,
                            // alignItems:'flex-end',
                            // justifyContent:'center',
                          // borderBottomColor: '#DDDDDD',
                          // borderBottomWidth: 1,
                          }}
                          ></View>
</View> 
{
  this.state.isLoading &&
  <Spinner />
               } 
               {
  !this.state.isLoading &&
   null
               } 
{/* </KeyboardAvoidingView> */}
            {/* </ImageBackground> */}
            


            </View>
            
        )
    }
}


const styles = StyleSheet.create({
  container: { 
    flex:1,
    width: '100%',
height:'100%',
backgroundColor:'#F2F1F1',
// alignItems:'center',
// justifyContent:'center'
},
  logoContainer: {
      alignItems: 'center',
      justifyContent: 'flex-start',
      //flex: 1,
      top:20
  },
  logo: {
      width: 43, 
      height: 60,
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
      // position: 'absolute',
      // left: 0,
      // right: 0,
      // top: 50,
      height: 200,
      paddingHorizontal: 40, 
  },
  input: {
      height: 40,
      backgroundColor: '#fff',
      color: '#000',
      marginBottom: 15,
      paddingHorizontal: 10,
      textAlign:'left'
      ,
      borderRadius:10  ,
      fontSize:17
  },
  buttonContainer: {
      // flex:1,
      backgroundColor: '#f7c744',
      paddingVertical: 10,
      width:'100%',
      borderRadius:10,
      marginTop:20   
  },
  buttonText: {
      textAlign: 'center',
      color :'#020202',
      // fontWeight: 'bold',
      fontSize: 12,  
      paddingHorizontal:10,
      top: 5,   
      // fontFamily: "Montserrat-SemiBold",
},
closeStyle: {
  marginTop: 22,
    },
    loginButtonContainer:{
      position: 'absolute',
      left: 0,
      right: 0,
      bottom: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 80 : 100) : 180,
      height: 150,
      padding: 15,
      marginHorizontal:80,
      //alignItems:'flex-end',
      justifyContent: 'flex-end',
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
    },
    text_login:{
      textAlign: 'center',
      color :'#ffffff',
      fontSize: 20,
      fontFamily:'Montserrat-SemiBold',
      marginBottom: 10 
    },

    profileImage: {
      
      justifyContent: 'center',
        alignItems:"center",
        margin: 22,
        paddingTop: ( Platform.OS === 'ios' ) ? 35 : 40,
        // marginLeft:10
    },

    loginButtonSection: {
      marginHorizontal:70,
      backgroundColor: '#0078D6',
      //justifyContent: 'center',
      alignItems: 'center'
   },

   

   loginButton: {
     //backgroundColor: 'blue',
     color: 'white'
   },
   InstagramText: {
    textAlign: 'center',
    color :'#000000',
    fontWeight: 'bold',
    fontSize: 25,
    paddingHorizontal:10,
    fontFamily: "Billabong",
},
InstagramIcon:{
  marginTop:6,
  width:140,
  height:37
},

backdropStyle: {
  // position: 'relative',
  // top: 0,
  // bottom: 0,
  // left: 0,
  // right: 0,
  backgroundColor: 'rgba(0,0,0,0.3)',
  padding: 50,
  alignItems:'center',
  justifyContent: 'center'
},
modalStyle:{
  backgroundColor: '#fff',
  borderRadius: 5,
  // maxWidth: 100,
  // minHeight: 150,
  width:'80%',
  height: 250,
  margin: 0,
  padding: 30,
  
},
headerIcon: {
  // textAlign : 'left',
  // marginLeft: 15,
  // width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
  // height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
  borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
  marginLeft:8,
  ...ifIphoneX(
    {
      // height: 50,
      marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 10 : 20 : 10,
    },
    {
      // height: 55
      marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 10 : 20 : 10,

    }
  )
  
},
})