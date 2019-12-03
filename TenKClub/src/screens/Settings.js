/*
Setting Screen
*/
import React, { Component } from 'react'
import { Images, Colors } from '../themes'
import _ from "lodash";
import {
    Platform,
    StyleSheet, Text, View, Image,
    TouchableWithoutFeedback, StatusBar,
    TextInput, SafeAreaView, Keyboard, TouchableOpacity,
    KeyboardAvoidingView, ImageBackground, Modal, Alert, TouchableHighlight, BackHandler, ToastAndroid,
    AlertIOS, Dimensions, ScrollView, I18nManager
} from 'react-native'
import AsyncStorage from '@react-native-community/async-storage';
import { getUserProfile, updateUserProfile } from "../constants/apis";
import Spinner from "../components/Spinner";
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import LinearGradient from 'react-native-linear-gradient';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
import { ifIphoneX } from 'react-native-iphone-x-helper'
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
import DatePicker from 'react-native-datepicker'
import FlipToggle from 'react-native-flip-toggle-button';
import Webbrowser from 'react-native-custom-webview'
import images from '../themes/Images';
import Cookie from 'react-native-cookie'

// I18nManager.forceRTL(true); 
const isRTL = I18nManager.isRTL;

export default class Settings extends Component {
    constructor(props) {
        super(props)
        this.state = {
         isActive:false,
         isSwitch1On: false,
         name:"",
        }
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    }




    async logout()
    {

        // logout() {
            Cookie.clear().then(() => {
                // alert();
            //   this.setState({ token: null })
            })
        //   }
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
                   this.props.navigation.navigate('LoginScreen');
                   //this.props.navigation.navigate('Home');
                    // this.props.navigation.navigate('storyList');
                }
                    
            });
    }


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
             AsyncStorage.multiGet(['member_username','user_notification_status']).then((data) => {
                var nm = data[0][1];
                var user_notification_status = data[1][1];
                if(user_notification_status == 1){
                    this.setState({
                        isSwitch1On: true
                       })
                }else if(user_notification_status ==2 ){
                    this.setState({
                        isSwitch1On: false
                       })
                }
                
                this.setState({
                    name: nm
                   })
                // alert(this.state.name)
                

    

            //     if (username !== null && username !== ''){
            //         //Your logic
            //         this.props.navigation.navigate('Profile');
            //         return false;
            //     }else{
            //         this.props.navigation.navigate('Login');
            //     }

            });


        }
        catch (error) {

            console.log('error' + error)
        }
    }
    async notification_toggle(value){
            // alert(value);
            this.setState({ isSwitch1On: value });
            if(value == true){
                var notification_offset = 1;
                var notification_value = '1';
            }else{
                var notification_offset = 2;
                var notification_value = '2';
            }
              
            let username = 'mohan.das.99990';
      // let password = '';
      let session_user_id = 2;
      await AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
          username = data[0][1];
          // password = data[1][1];
          session_user_id = data[2][1];
        });
      //   alert(session_user_id);
      //   return false;
      this.setState({ isLoading: true });
      let formBody = "user_id="+session_user_id+"&user_notification_status="+notification_offset+"";
      // alert("top");
      if (username !== null && username !== ''){

      
            await fetch(
              // "http://192.168.1.23/10k-club/webservices/edit_user_profile.php/",
              // 'http://10k.tempurl.co.il/webservices/edit_user_profile.php',
              updateUserProfile,
              {
                method: "POST",
                headers: {
                  Authorization: "Bearer token",
                  "Content-Type": "application/x-www-form-urlencoded"
                },
                body: formBody
              }
            )
              .then(response => response.json())
              .then(responseData => {
                // this.setState({ isLoading: false });
                // this.props.navigation.navigate("Favourites");
                AsyncStorage.multiSet([
                    ["user_notification_status", notification_value]
                    ]);
                
              })
              .catch(error => {
                // this.setState({ isLoading: false });
                console.log(error);
              });
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

    render() {
        return (
            <ImageBackground source={Images.screen_settings} resizeMode="stretch" style={styles.container}>
                {/* <SafeAreaView style={styles.container}> */}


                {/* <StatusBar barStyle="light-content" /> */}



                {/* <ScrollView> */}
                {/* <View style={{paddingTop:20, flex:1}}>
                <Webbrowser
                    url="https://facebook.github.io/react-native/docs/"
                    hideHomeButton={false}
                    hideToolbar={false}
                    hideAddressBar={false}
                    hideStatusBar={true}
                    backButtonVisible={true}
                    onBackPress= {() => {goBack()}}
                    foregroundColor="#D61B5D"
                    backgroundColor="#F3848A"
                />
                
            </View> */}

                <View style={styles.mainContainer}>
                <View>
                <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.setting_back} style={styles.headerIcon} />
                    </TouchableOpacity> 
                    </View>
                    <KeyboardAwareScrollView>
                    
                        <ScrollView style={{bottom:35}}>
                        
                            <View style={styles.logoContainer}>
                           
                                {/* <View style={styles.logoContainer}> */}
                                

                                <Image style={styles.logo}
                                    // source={require('../images/logo.png')}> 
                                    source={Images.iconSettingScreen}>
                                </Image>

                                <Text style={styles.text_register}>SETTINGS</Text>





                                {/* </View> */}

                            </View>
                            <View style={styles.headerview}>
                            </View>
                            <Text style={styles.headingview}>Account</Text>

                            <View style={styles.semiheadingTop}>
                            <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Edit profile</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('Registration')}>
                                    <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>    
                                    </TouchableOpacity>              
                                    </View>

                                    
                                    </View>
                                    {/* {2 st} */}
                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('PaymentScreen_1')}>
                                    <Text style={styles.semitext}>Payment</Text>
                                    </TouchableOpacity>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
  
                                    </View>   
                                {/* {2 end} */}







                                <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Password</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {3 end} */}



                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Language</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {4 end} */}

                                    


                            </View>
                            <Text style={styles.headingview}>Privacy and Security</Text>
                            <View style={styles.semiheadingTop}>
                            <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Activity Status</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>    */}
                                                
                                    </View>
                                   
                                      </View>  
                                    {/* {5 end} */}

                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Saved Login Info</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {6 end} */}

                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Account Data</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {7 end} */}


                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Contact Syncing</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {8 end} */}


                                    <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.9, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Privacy and Security Help</Text>
                                </View>
                                <View style={{ flex: 0.3, alignItems: "flex-end" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('Privacy_and_Security_Help')}>
                                    <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>   
                                    </TouchableOpacity>                
                                    </View>
                                   
                                      </View>  
                                    {/* {9 end} */}
                                    </View>



                                    <Text style={styles.headingview}>Notification</Text>
                            <View style={styles.semiheadingTop}>
                            <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Push Notification</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end",marginTop:7 }}>
                                <FlipToggle
            value={this.state.isSwitch1On}
            buttonWidth={80}
            buttonHeight={25}
            buttonRadius={50}
            buttonOffColor={'#666'}
            buttonOnColor={'#666'}
            sliderOffColor={'#C70039'}
            sliderOnColor={'green'}
            onLabel={'On'}
            offLabel={'Off'}
            labelStyle={{ color: 'white',fontWeight:'bold'}}
            onToggle={(value) => {
              this.notification_toggle(value);
              
            }}
            onToggleLongPress={() => {
              console.log('Long Press');
            }}
          />
                                    </View>
                                   
                                      </View>  
                                    {/* {10 end} */}

                                    <View style={styles.semiheadingview}>
                                    {/* <TouchableOpacity onPress={() => this.props.navigation.navigate('Notification_list')}> */}
                                <View style={{ flex:1, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Email and SMS Notification</Text>
                                </View>
                                {/* </TouchableOpacity> */}
                                <View style={{ flex: 0.1, alignItems: "flex-end" }}>
                                    {/* <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>                   */}
                                    </View>
                                   
                                      </View>  
                                    {/* {11 end} */}
                                    

                                    
                                   
                                    
                                    </View> 

                                    <Text style={styles.headingview}>Support</Text>
                            <View style={styles.semiheadingTop}>
                            <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Help center</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('Helpcenter')}>
                                    <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>    
                                    </TouchableOpacity>                
                                    </View>
                                   
                                      </View>  
                                    {/* {10 end} */}

                                    <View style={styles.semiheadingview}>
                                <View style={{ flex:1, alignItems: "flex-start" }}>
                                    <Text style={styles.semitext}>Report a Problem</Text>
                                </View>
                                <View style={{ flex: 0.1, alignItems: "flex-end" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('ReportProblem')}>
                                    <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>    
                                    </TouchableOpacity>                 
                                    </View>
                                   
                                      </View>  
                                    {/* {11 end} */}
                                    

                                    
                                   
                                    
                                    </View> 
                                    
                                      
                                    
                            <View style={styles.semiheadingTop}>
                            <View style={styles.semiheadingview}>
                                <View style={{ flex: 0.5, alignItems: "flex-start" }}>
                                    <Text style={{color: '#000000',fontSize: 21,
        fontFamily: "Montserrat-Regular",}}>About &amp; Terms</Text>
                                </View>
                                <View style={{ flex: 0.5, alignItems: "flex-end" }}>
                                <TouchableOpacity onPress={() => this.props.navigation.navigate('About')}>
                                    <Image style={styles.semiicon}
                                        // source={require('../images/logo.png')}> 
                                        source={Images.sideArrow}>
                                    </Image>    
                                    </TouchableOpacity>                 
                                    </View>
                                   
                                      </View>  
                                    {/* {10 end} */}

                                    
                                   
                                    

                                    
                                   
                                    
                                    </View> 
                                    {/* <Text style={styles.headingview}>Log Out</Text> */}
                                    <View style={{  flexDirection: 'row'}}> 
                                    
                                     <TouchableOpacity onPress={()=> this.logout()}>
                                    <View>
                                    <Text style={styles.headingview}>Log Out  </Text>
                                    </View>
                                    </TouchableOpacity>
                                    <Text style={styles.username}>{this.state.name}</Text>
                                    </View>
                        </ScrollView>
                    </KeyboardAwareScrollView>

                    {
                        //this.state.isLoading &&
                        // <Spinner />
                    }

                </View>
                {/* </ScrollView> */}
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



                {/* </SafeAreaView> */}

            </ImageBackground>
        )
    }
}
const styles = StyleSheet.create({
    container: {
        flex: 1,
        width: '100%',
        height: '100%'
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'flex-start',
        flex: 1,
        top: 35

    },
    logo: {
        width: 40,
        height: 43,
    },
    headerview: {


        justifyContent: 'flex-start',
        flex: 1,
        marginTop: 40,
        borderBottomWidth: 1.5,
        borderBottomColor: '#AFADAC',
        marginHorizontal: 25
    },

    ista_logo: {
        width: 25,
        height: 25,
    },
    buttonContainer: {
        // flex:1,
        backgroundColor: '#f7c744',
        paddingVertical: 15
    },
    buttonText: {
        textAlign: 'center',
        color: '#ffffff',
        // fontWeight: 'bold',
        fontSize: 16,
        paddingHorizontal: 10,
        // fontFamily: "Billabong",
    },
    createAccountButton: {
        //backgroundColor: 'transparent',
        paddingVertical: 10,
        borderRadius: 100,
        borderWidth: 1,
        borderColor: 'transparent',
        flexDirection: 'row',
        alignItems: 'center',

        justifyContent: 'center'

    },
    text_register: {
        textAlign: 'center',
        color: '#000000',
        fontSize: 20,
        fontFamily: 'Montserrat-SemiBold',
        marginTop: 10
    },

    headingview: {
        textAlign: 'left',
        color: '#000000',
        fontSize: Platform.OS === "ios" ? 23 : 21,
        fontFamily: "Montserrat-Regular",
        marginTop: 27,
        marginHorizontal: 20
    },
    semiheadingTop: {
        flex: 1,
        //flexDirection: 'row',
        marginTop: 11,
        marginHorizontal: 20
    },
    semiheadingview: {
        flex: 1,
        flexDirection: 'row',
        marginTop:4,
        //marginHorizontal: 20
    },
    semitext: {
        color: '#000000',
        fontSize: Platform.OS === "ios" ? 20 : 18,
        marginTop: 10,
        //marginHorizontal:5
    },
    semiicon: {
        width: 12,
        height: 12,
        marginTop: 15,
    },
    bodyView: {
        flex: 1,
        backgroundColor: "transparent"
    },
    inputContainer: {
        flex: 1,
        marginHorizontal: 30,

    },
    textInputView: {
        flex: 1,
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
    input: {
        marginLeft: 5,
        fontSize: 15,
        height: 50,
        color: "#fff",
        // flexDirection: isRTL ? "row-reverse" : "row",
        textAlign: isRTL ? "right" : "left",
    },
    lableTextView: {
        flex: 1,
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

    PhoneNumberInput: {
        marginRight: 15,
        fontSize: 15,
        height: 50,
        color: "#fff",
        // flexDirection: "row-reverse",
        textAlign: "right",
    },
    //   phoneNumberMainView:{
    //     flex:1, flexDirection: "row" 
    //   },
    phoneNumberCountryCodeViewRTL: {
        flex: 0.2, borderRightWidth: 1, borderColor: "#34393b", flexDirection: isRTL ? "row-reverse" : "row"
    },
    phoneNumberCountryCodeViewLTR: {
        flex: 0.2, borderRightWidth: 1, borderColor: "#34393b"
    },
    phoneNumberValueViewRTL: {
        flex: 0.9, marginLeft: 5, flexDirection: isRTL ? "row-reverse" : "row"
    },
    phoneNumberValueViewLTR: {
        flex: 0.9, marginLeft: 5
    },
    AddressViewRTL: {
        flex: 0.6, borderRightWidth: 1, borderColor: "#34393b", flexDirection: isRTL ? "row-reverse" : "row"
    },
    AddressViewLTR: {
        flex: 0.6, borderRightWidth: 1, borderColor: "#34393b"
    },
    postalCodeViewRTL: {
        flex: 0.4, marginLeft: 5, flexDirection: isRTL ? "row-reverse" : "row"
    },
    postalCodeViewLTR: {
        flex: 0.4, marginLeft: 5
    },
    createAccountButtonView: {
        flex: 1,
        marginTop: 30,
        marginHorizontal: 30,
        marginBottom: 40
    },
    textboxfieldd: {
        fontFamily: 'Montserrat-Regular'
    },
    mainContainer: {
        marginTop: Platform.OS === "ios" ? 30 : 0,
        marginBottom: Platform.OS === "ios" ? 30 : 30,
        
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
    username: {
        textAlign: 'left',
        color: '#000000',
        fontSize: 20,
       
        marginTop: 27,
        // marginHorizontal: 20
    },

    //   inoutViewMulti: {
    //     flex: 0.6,
    //     height: 80,
    //     backgroundColor: Colors.colorlightgray,
    //     borderRadius: 5,
    //     borderWidth: 0.5,
    //     borderColor: "gray",
    //     marginTop: 5,
    //     //border: 'none'

    //   },
    //   inputMulti: {
    //     marginLeft: 10,
    //     fontSize: 15,
    //     height: 80,
    //     marginTop: -10
    //   },
    //   inputMain: {
    //     marginTop: 15,
    //     flexDirection: isRTL ? "row-reverse" : "row",
    //     alignItems: "center",
    //     color:"#fff"
    //   },
    //   titleView: {
    //     alignItems: "center",
    //     justifyContent: "center",
    //     height: 55,
    //     backgroundColor: Colors.colorWhite,
    //     elevation: 7,
    //     shadowOpacity: 7
    //   },

    //   title: {
    //     textAlign: "center",
    //     fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    //     color: Colors.orangeColor
    //   },



})