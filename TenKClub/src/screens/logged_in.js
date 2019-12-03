/*
Loggedin Screen
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
import { getUserProfile, updateUserProfile } from "../constants/apis";
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

export default class logged_in extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            username:'',
            password:'',

            firstName: "",
      lastName: "",
      userID: "",
      Email: "",
      eventType: "",
      Age:"",
      profileImage:""
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
         
            AsyncStorage.multiSet([
              ["username", res.user_details.username],
              // ["password", this.state.password],
              ["user_id", res.user_details.user_id],
              ["user_notification_status", res.user_details.user_notification_status],
              ["is_profile_updated", res.user_details.is_profile_updated]
              
              ]);
           
            
            this.setState({ 
              profileData: res.user_details,
              FavouritesData: res.favourites,
              firstName : res.user_details.first_name,
              lastName : res.user_details.last_name,
              userID : res.user_details.member_username,
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
	

      async switcaccount()
{

  Cookie.clear().then(() => {
    // alert();
//   this.setState({ token: null })
})
    AsyncStorage.multiSet([
        ["username", ""],
        ["password", ""],
        ["user_id", ""],
        ["user_notification_status", ""],
        ["is_profile_updated", ""]
        
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
               this.props.navigation.navigate('LoginScreen');
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

              this.getUserDetail();
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
    async onContinueOption(){
      var guideline_flag = await AsyncStorage.multiGet(['is_profile_updated']).then((data) => {
        let is_profile_updated = data[0][1];
        return is_profile_updated;
      });

      if(guideline_flag == 1){
        this.props.navigation.navigate('Registration');
       }else{
        // this.props.navigation.navigate("Home");
        this.props.navigation.navigate('Tabs');
       }
    }
    render() {
        return (
            <ImageBackground source={Images.screen_1_back}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
                
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
                                {/* <Text style={styles.buttonText}>
                                    Instagram  
                                    </Text> */}
                            </View>
                            
                            </View>

                            <View style={styles.profileImage}>
                    <Image source={{uri:this.state.profileImage}}
              style={{width: 120, height: 120, borderRadius: 120/2}} />
              
                    </View>

                    <View style={styles.loginButtonSection}>
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
        fontSize: 30,
        paddingHorizontal:10,
        top: 15,
        fontFamily: "Billabong",
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
     }
})