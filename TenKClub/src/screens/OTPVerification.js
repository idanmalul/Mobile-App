/*
OTP Verification Screen
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
    KeyboardAvoidingView,ImageBackground,Modal,Alert,TouchableHighlight,BackHandler,ToastAndroid,Button,
    AlertIOS,Dimensions,ScrollView,I18nManager
} from 'react-native'
import AsyncStorage from '@react-native-community/async-storage';
//I18nManager.forceRTL(true); 
import { userLogin } from "../constants/apis";
import Spinner from "../components/Spinner";
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import TimerMixin from 'react-timer-mixin';
import ReactTimeout from 'react-timeout';
import LinearGradient from 'react-native-linear-gradient';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
import { ifIphoneX } from 'react-native-iphone-x-helper'
import { getUserProfile, updateUserProfile,verifyOTP } from "../constants/apis";
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

import DialogInput from 'react-native-dialog-input';
const isRTL = I18nManager.isRTL;
export default class OTPVerification extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            username:'',
            password:'',

            verificationCode: '',
            isDialogVisible: true
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
      this.props.navigation.navigate('Login');

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


      getUserDetail = ()=>{
        multipleData = [];
        AsyncStorage.multiGet(['username', 'password']).then((data) => {
          let username = data[0][1];
          let password = data[1][1];
          let formBody = "username="+username;
          // setTimeout(() => {
          //   this.setState({ isLoading: false });
          // }, 10000);
          if (username !== null && username !== ''){
          fetch(
                // 'http://192.168.1.23/10k-club/webservices/get_user_profile.php',
                // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
                getUserProfile,
                {
                  method:'POST',
                  headers: {
                    Authorization: "Bearer token",
                    "Content-Type": "application/x-www-form-urlencoded"
                    // Accept: 'application/json',
                    // 'Content-Type': 'application/json',
                  },
                body: formBody,
              
              })
        
        .then((response)=> response.json())
        
        .then((res) => {
          if(res.status === 'true'){
         
            
           
            
            this.setState({ 
              profileData: res.user_details,
              FavouritesData: res.favourites,
              firstName : res.user_details.first_name,
              lastName : res.user_details.last_name,
              userID : res.user_details.username,
              Email : res.user_details.email,
              user_id : res.user_details.user_id,
              Age :res.user_details.age,
              profileImage:res.user_details.profile_pic_url,

              
            });
            this.setState({ isLoading: false });
            // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
            //this.setState.multipleSelectedData.includes(res.user_details.favourites);
          }
          else {
            this.setState({ isLoading: false });
            
          }
        
        
        })
        
         .done();
      }
    });
        
        }

        async verifyOTP() {
        
        
        
          
          //    return false;
              
              if (this.state.verificationCode == "") {
                  Platform.select({
                    ios: () => {
                      AlertIOS.alert("Please enter verification code.");
                    },
                    android: () => {
                      // ToastAndroid.show("Please enter username", ToastAndroid.LONG, ToastAndroid.CENTER);
                      ToastAndroid.showWithGravityAndOffset(
                          "Please enter verification code.",
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
                
               this.setState({ isLoading: true });
              let fcmToken = await AsyncStorage.getItem('fcmToken');
              let challenge_user_id;
              await AsyncStorage.multiGet(['challenge_user_id']).then((data) => {
                challenge_user_id = data[0][1];
                // let password = data[1][1];
                // let formBody = "username="+username;
              });
              // alert(fcmToken);
              // return false;
              let formBody="user_id="+challenge_user_id+"&verification_code="+this.state.verificationCode+"&gcm_id="+fcmToken+"&device_name="+""+"";
              // setTimeout(() => {
              //     this.setState({ isLoading: false });
              //   }, 15000);
             fetch(
                  // "http://192.168.1.23/10k-club/webservices/first.php/",
              //    "http://10k.tempurl.co.il/webservices/first.php/",
              verifyOTP,
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
                          
                          ])
                          console.log("login true");
                          
                          // setTimeout(() => {
                          //     this.setState({ showPopup: false });
                          //         this.setState({ isLoading: false });
                                  
                          //       }, 15000);
                          //this.closeActivityIndicator();
                          // this.setState({
                          //     isLoading: false
                          //     })
                          //     setTimeout(() => {
                          //     this.setState({ showPopup: false });
                          //   }, 100);
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
                          AsyncStorage.multiSet([
                            ["username", ""],
                            ["password", ""],
                            ["user_id", ""],
                            ["user_notification_status", ""],
                            ["is_profile_updated", ""]
                            
                            ])
                          
                            setTimeout(() => {
                              Alert.alert(
                                  'Alert',
                                  responseData.message,
                                  [
                                   // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
                                  //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
                                    {text: 'OK', onPress: () => this.onAlertMessageOk()},
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

            onAlertMessageOk(){
              this.setState({ isLoading: false });
              this.props.navigation.navigate('Login');
            }
	

      async switcaccount()
{
    AsyncStorage.multiSet([
        ["username", ""],
        ["password", ""],
        ["user_id", ""]
        
        ])

        AsyncStorage.multiGet(['username', 'password']).then((data) => {
            let username = data[0][1];
            let password = data[1][1];
            
            if (username !== null && username !== ''){
            //    this.props.navigation.navigate('Profile');
                //  this.props.navigation.navigate('Tabs');
            //    this.props.navigation.navigate('Settings');
               //this.props.navigation.navigate('Home');
            // this.props.navigation.navigate('Home');
                return false;
            }else{
                //  this.props.navigation.navigate('Login');
               this.props.navigation.navigate('Login');
               //this.props.navigation.navigate('Home');
                // this.props.navigation.navigate('storyList');
            }
                
        });
}

    // closeActivityIndicator = () => setTimeout(() => this.setState({
    //     isLoading: false,showPopup: false }), 10000)

      async componentWillMount() {
        try {
            BackHandler.addEventListener(
                "hardwareBackPress",
                this.handleBackPress
              );

             // this.getUserDetail();
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
            <ImageBackground source={Images.screen_1_back}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
                <ScrollView>
                <StatusBar barStyle="light-content"/>
                {/* <KeyboardAvoidingView behavior='padding' style={styles.container}> */}
                    {/* <TouchableWithoutFeedback style={styles.container}  */}
                            {/* onPress={() => this.setState({ showPopup: false })}> */}
                            
                        
                        <View style={styles.logoContainer}>
						
                            <View style={styles.logoContainer}>
                            

                               <Image style={styles.logo}
                                    // source={require('../images/logo.png')}> 
                                    source={require('../images/logo_login.png')}> 
                                </Image>
                                <Text style={styles.text_register}>Stay Secure</Text> 
                                <View style={{marginHorizontal:20}}>
                                {/* <Text style={{color:"#fff"}}>
                                {'\n'}
                                You are just a step away. For security reason to continue using 10K Club application we request you to execute the below steps:{"\n\n"}
1. You just have received an OTP either on your email or Mobile phone registered with your Instagram account.{'\n'}
2. Enter the OTP and click on Verify{'\n'}
3. Login to your Instagram account through Instragram Application or Web and enable two factor authentication.{'\n'}
4. Now, Login through 10K Club application.</Text> */}
<Text style={{color:"#fff"}}>
                                {'\n'}
                                You are just one step away from officially being part of the 10K community! You have just received an OTP to either your email or phone number registered with your Instagram account. For security purposes, please execute the following steps: {"\n\n"}
1. Enter the OTP below{'\n'}
2. Click Verify{'\n'}
3. Enable two factor authentication on your Instagram account.{'\n'}
4. Login to the 10K Club application with your Instagram username and password.</Text>
                                </View>

                                {/* <View >
  <Text style={{color:"#fff"}}>
  Hello This is an example of 
    {'\n'}
    multiline text
  </Text>
  <Text style={{color:"#fff"}}>{`Here is an other way to 
  set multiline text.`}</Text>
</View> */}
                            </View>
                            
                            </View>

                            {/* <View style={styles.profileImage}>
                    <Image source={{uri:this.state.profileImage}}
              style={{width: 120, height: 120, borderRadius: 120/2}} />
              
                    </View>

                    <View style={styles.loginButtonSection}>
     <TouchableOpacity  
             style={styles.loginButton} onPress={()=> this.props.navigation.navigate('Home')}>
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

                            
{/* <DialogInput isDialogVisible={this.state.isDialogVisible}
            title={"OTP Verification"}
            message={"Please enter OTP that you have received on email or phone."}
            hintInput ={"OTP"}
            submitInput={ (inputText) => {this.sendInput(inputText)} }
            closeDialog={ () => {this.showDialog(false)}}
            // onPress={ () => {this.showDialog(false)}}
            textInputProps={{keyboardType:Number,closeOnTouchOutside:false}} 
            >
</DialogInput> */}
                <View style={styles.bodyView}>
                   {/* 7th field : start */}
                <View style={styles.inputContainer}>
                    {/* <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        STATE
                        </Text>
                    </View> */}
                    
                    <View style={styles.textInputView}>
                    
                        <TextInput
                        style={styles.input}
                        placeholder="Enter OTP"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        // placeholderTextFontFamily= 'Montserrat-Regular'
                        keyboardType="number-pad"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ verificationCode: text })}
                        value={this.state.verificationCode}
                        returnKeyType="next"
                        />
                    </View>
                
                {/* end */}

                {/* Create Account Button : start */}
                {/* <View style={styles.loginButtonContainer}>
                            <Text style={styles.text_login}> LOG IN BY</Text> 
                            <TouchableOpacity 
                            onPress={() => this.openPopup()}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#f6f6f6', '#d7d7d7', '#b3b3b3']}style={styles.loginButton}>
                            
                            
                            <Image style={styles.ista_logo} 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image>
                                    <Text style={styles.buttonText}>
                                    Instagram
                                    </Text>
                                   
                                
                                </LinearGradient>
                                </TouchableOpacity>
                            </View>  */}
                            <View style={styles.createAccountButtonView}>
                            <TouchableOpacity 
                            onPress={() => {
                                this.verifyOTP();
                                borderColor="transparent"
                              }}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#e0f9f5', '#61e1ce', '#22d5ba']} style={styles.createAccountButton}>
                            
                            
                            {/* <Image style={styles.ista_logo} 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image> */}
                               
                                
                                <Text style={styles.buttonText}>
                                Verify OTP
                                </Text>
                                
                                    
                                   
                                
                                </LinearGradient>
                                </TouchableOpacity>
                            </View>
                            </View>
                {/* end */}
                </View>
                </ScrollView>
                {
						this.state.isLoading &&
						<Spinner />
                         }
                
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
        //flex: 1,
        top:20
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
        top: 50,
        height: 200,
        padding: 20,
    },
    // input: {
    //     height: 40,
    //     backgroundColor: 'rgba(255,255,255,0.2)',
    //     color: '#FFF',
    //     marginBottom: 20,
    //     paddingHorizontal: 10,
    //     textAlign:'right' 
    // },
    buttonContainer: {
        // flex:1,
        backgroundColor: '#f7c744',
        paddingVertical: 15
    },
  //   buttonText: {
  //       textAlign: 'center',
  //       color :'#ffffff',
  //       fontWeight: 'bold',
  //       fontSize: 30,
  //       paddingHorizontal:10,
  //       top: 15,
  //       fontFamily: "Billabong",
	// },
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
          margin: 30,
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

     bodyView: { 
      flex:1,
      backgroundColor: "transparent",
      marginTop: 80 
    },
    inputContainer: {
      flex:1,
      marginHorizontal: 40,
      // height: 50
      
  },
  buttonText: {
    textAlign: 'center',
    color :'#ffffff',
    // fontWeight: 'bold',
    fontSize: 16,
    paddingHorizontal:10,
    // fontFamily: "Billabong",
},
input: {
  marginLeft: 5,
  fontSize: 15,
  height: 50,
  color:"#fff",
  // flexDirection: isRTL ? "row-reverse" : "row",
  textAlign: isRTL ? "right" : "left",
},
  createAccountButton: {
    //backgroundColor: 'transparent',
    paddingVertical: 10, 
    borderRadius:100,
    borderWidth: 1,
    borderColor: 'transparent',
    flexDirection:'row',
    alignItems: 'center',
     
    justifyContent:'center'

  },
     createAccountButtonView: {
      flex:1,
      marginTop:30,
      marginHorizontal: 30,
      marginBottom: 40
  },
  lableTextView: {
    flex:1,
    flexDirection: isRTL ? "row" : "row",
    marginLeft: 5,
    // textAlign: isRTL ? "left" : "right",
    // alignItems: !isRTL ? "flex-start" : "flex-end",
  },
  lableText: {
      
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 14, 
    color: "#fff",
    marginTop: 15,
    fontFamily: 'Montserrat-Regular'
    
  },
  textInputView: {
    // flex: 1,
    height: 50,
    backgroundColor: "transparent",
    //borderRadius: 0,
   // borderWidth: 0,
  //  borderColor: "gray",
    marginTop: 5,
    borderColor: '#34393b',
    borderWidth: 1,
    // marginLeft: 20,
    // marginHorizontal: 20
    
  },
//   textboxfieldd:{
//     fontFamily: 'Montserrat-Regular'
// },
text_register:{
  textAlign: 'center',
  color :'#ffffff',
  fontSize: 20,
  fontFamily:'Montserrat-SemiBold',
 marginTop: 10 
},
})