/*
Login Screen Design
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
import { userLogin,userNormalLogin } from "../constants/apis";
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

var htmlstring_2 = `10K CLUB App requesting to do the following:
      
- Access your basic information.                      
- Your media and profile info.
- Save the data at 10K CLUB server.
- You will login using Instagram API to use the functionality of 10K CLUB Application.`;  

import DialogInput from 'react-native-dialog-input';
import Dialog, { DialogFooter, DialogButton, DialogContent } from 'react-native-popup-dialog';

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
    showPrivacyPolicy(){
      var htmlstring_2 = `10K CLUB App requesting to do the following:
      
- Access your basic information.                      
- Your media and profile info.
- Save the data at 10K CLUB server.
- You will be login using Instagram API to use the functionality of 10K CLUB Application.`;  
      Alert.alert(
        '',
        htmlstring_2,
        [
         // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
        //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
        {text: 'Cancel', onPress: () => this.props.navigation.navigate('Login'), style: 'cancel'},
          {text: 'Authorize', onPress: () => console.log('Agreed by user.'), style: 'ok' },
          
        ],
        { cancelable: false }
        , {
          contentIsHtml: true
         }
      );
      return false;

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

      var htmlstring = `10k club Israel , a Delaware corporation, from 150 East 58th Street, Suite 2400 New York, NY 10155 (“10K Club"), manages and operates, directly and Indirectly, a platform (the “Platform") that allows influencers to participate in campaigns initiated by third parties contracting with 10K Club in scope of ordering and developing campaigns (“Advertisers’) and offered to them through the Platform.

      The privacy of all users of the Platform is very important for us at 10K Club and we are committed to practicing high standards in order to protect the User's privacy.
      
      As part of such commitment, we have created this Privacy Policy which transparently summarizes how We collect, use and disclose information regarding the users on and through the Platform.
      
      This Privacy Policy is an integral part of 10K Club's Terms and Conditions and should be read together and as an integral part thereof.
      
      In this Privacy Policy, “User” or “You” shall mean any visitor to, or user of, the Platform (Influencer or Advertiser) on any mean on which the Platform is offered, including, without limitation, on our website, www.10k-club.com and on other websites and mobile and desktop applications, owned or managed by 10K Club and affiliates thereof in which this Privacy Policy is posted or published.
      
      This Privacy Policy applies to information regarding Users collected online and as part of any offline communication between User and 10K Club. We find it important to mention that this Privacy Policy does not apply to information collected from our employees and contractors.
      
      You are advised to review this Privacy Policy every time You visit the Platform, as 10K Club may update it from time to time. If there are material changes in the way 10K Club uses User's personally identifiable information, We will usually notify You by prominently posting a notice of such changes on the Platform or by sending you an e-mail. However, it is clarified that in any case, whether such notice was sent or not, such change will be binding to all Users regarding the collection, disclosure and use of Users information, from the date updated herein.
      
      What and How do We collect:
      
      Information that we collect can be “personally identifiable", meaning any information which could be utilized to identify or be readily associated with User. Personally identifiable information we collect may include, but is not limited to, User name, address, telephone number, e-mail address, or any other information of personal nature. Other personally identifiable information that we may collect, includes passwords, credit/debit card numbers, bank and other account details, etc.
      
      In order to operate the Platform and, amongst other, provide Users with information on events and services that may be of interest to them, We also collect demographic information (zip code, hometown, gender, purchase history, age and similar information) from Users.
      
      We collect information in two ways:
      
      (1) information that is knowingly and voluntarily input into the Platform directly by User in response to particular prompts or fields, or that is otherwise knowingly and voluntarily provided by User (such as in an e-mail or in a text massage)
      
      Whenever You are prompted by the Platform to complete forms, provide information or make choices, and You do so, You are voluntarily providing information to Us. This information may include personally identifiable information. You are also voluntarily submitting information to Us when you communicate with Us by e-mail, text message or telephone. We suggest that You should not send personally identifiable information through unencrypted communication channels (e-mail, etc.).
      
      We find it important to note that when User chooses to access or connect to the Platform through social networking websites (facebook and like), certain information (“Social Net Information") may also be collected by Us. Social Neb may include Your social networking site connect credentials, information that You authorize such site to share with Us though permissions and e-mail account information. Social Net Information may include personally identifiable information about User and about other individuals that User authorized or provided to Us. In any case, 10K Club does not evade or circumvent personal privacy settings on any other website..
      
      When You provide Us with Social Net Information consisting of personally identifiable information about other individuals, We may communicate with these individuals in order to offer them products or services that may be of interest to them, and may also use this information for other purposes for which personally identifiable information may be used as described in this Privacy Policy.
      
      And,
      
      (2) information that is collected automatically when User enters and uses the Platform.
      
      Certain information is collected automatically from User when User enters and uses the Platform, including through the use of automated processes that the Platform uses.
      
      Our Platform, like many websites on the Internet, uses “cookies.” A cookie is a small text file automatically placed on Your computer's web browser that is used to recognize users who have previously visited the Platform. In general, these cookies may reside on Your computer for certain periods of time after You last accessed the Platform.
      
      We use cookies for various purposes, including, but not limited to, keeping track of User purchases and other activities on the Platform, delivering content specific to User interests, and if You choose, saving personal and financial information so User does not have to re-enter same each time he visits the Platform.
      
      It is hereby disclosed that third-party advertisers and advertising networks on the Platform may also place their own cookies on Your browser and collect information directly from You in this manner, although they do not have access to cookies set by Platform. Such third party cookies are set to help deliver advertisements to You that You might be interested in (both on our Site and other websites that you may visit in the future), to prevent you from seeing the same advertisements too many times and to conduct research. The use of these cookies and other automated technologies by such third parties to collect information are governed by each such third party's specific privacy policy. You understand that 10K Club may use a variety of companies to serve advertisements on the Platform.
      
      If you do not wish to accept cookies, you can set your browser to decline them, and if You wish to delete or disable cookies previously placed on your computer, You can delete same from browser's history. Unfortunately, if You choose to decline, delete or disable cookies, You may not be able to access certain areas, or use certain features, of Platform.
      
      In Addition, like most websites, each time User visits the Platform, We automatically collect his “internet protocol” address and other information regarding User's browsing history. An IP address is a unique series of numbers that identifies each computer connected to the Internet. An IP address can be used to provide information such as the date and time of a User's visit, the popularity of various website functions, and the amount of information transmitted to each user from a site. We use Your IP address, amongst other, to help in Users identification and to gather demographic information.
       
      In addition to cookies, 10K Club and its business affiliates may use web beacons, pixels and similar technologies, that assist in determining how many Users have visited certain pages or opened messages or newsletters. However, We do NOT use such technologies to collect personally identifiable information.
      
      How we Use the Information that We Collected:
      
      10K Club uses any information collected by it solely in accordance with this Privacy Policy and for the following:
      
      - provide customer support;
      
      - process User transactions and deliver the products and services that User has requested;
      
      - send User offers that User has asked for;
      
      - perform research and analysis about User's interest in the Platform’s content and in the products, services offered through the Platform;
      
      - address User regarding products or services that may be of interest to User, including from third parties;
      
      - develop and display content and advertising tailored to User's interests:
      
      - protect from fraud, hacking or other harm;
      
      - cooperate with legal, criminal or regulatory investigations or proceedings;
      
      - provide User with newsletters, RSS feeds, or other communications or services which User has signed up for or otherwise agreed to receive;
      
      - deliver account or transaction related communications to User;
      
      - monitor User's use of the Platform.
      
      Sharing Information we collected with third parties:
      
      User personally identifiable information is shared by us with third parties solely as specifically described below.
      
      10K Club never shares or makes available to third parties, any financial information collected by it (credit card details, bank details, etc.), except in connection with a transaction specifically made by User.
      
      It is important to understand that 10K Club acts solely as a platform to facilitate services to Advertisers and Influencers. Accordingly, Influencer should be aware that Advertiser may use Influencers's information in accordance with its own privacy policies. 10K Club has no control over such sharing and use of Influencer personally identifiable information by Advertiser.
      
      From time to time, where permitted by applicable law, 10K Club may share User's information collected by it (but not any financial data information) with reputable direct marketers so that they may offer Campaigns, products and services which 10K Club hope will be of interest to User. 
      
      In addition, We may disclose User information to contractors and service providers who are engaged by Us to perform certain functions related to the Platform (such as processing of payments, provision of data storage, marketing of products and services and conducting audits. In any case, We will make sure that such third party contractors and service providers shall receive the minimal personally identifiable information they need to provide their service.
      
      In addition, We may disclose User information to third parties when We believe, in good faith, that such disclosure is reasonably necessary to (a) enforce or apply our terms of service, including investigation of potential violations thereof, (b) comply with legal or regulatory requirements or cooperate with a law enforcement or regulatory investigation, (c) protect the rights, property or safety of any person, (d) prevent a crime or protect national security, or (e) detect, prevent or otherwise address fraud, security or technical issues. We may also disclose non- personally identifiable information to 10K Club’s business partners, investors, potential buyers and other third parties if We deem such disclosure to have sound business reasons or justifications.
      
      Certain information relating to usage of the Platform may be shared by Us with third parties on an aggregated, anonymous basis for research, analytical or promotional purposes. However, such information will not identify any User as the subject or source of the information. 
      
      Information about Users, including personally identifiable information, may be disclosed as part of any merger, acquisition, or sale of 10K Club and/or its assets, as well as in the event of insolvency, bankruptcy, or receivership, in which case personally identifiable information may be transferred as one of the business assets of the company.
      
      User Uploaded Content:

      The Platform may enable User to post information about himself, communicate with others, or otherwise upload content to the Platform.
      
      It is important to understand that whenever User discloses personally identifiable information publicly (such as on publicly viewable web pages or other parts of the Platform), that information becomes public and can be collected and used by others, including for the sending of unsolicited messages. 10K Club has no ability to control the identity of third parties to which such public information may be exposed.
      
      Users not from the United States
      
      The Platform is operated and hosted in the United States. If You are visiting the Site from outside the United States, please be aware that Your information may be transferred to, stored, and processed in the United States or in any other place in which our servers are located and our central database is operated. The data
      
      protection and privacy laws of the United States may not be as comprehensive as those in Your own country.
      
      By using the Platform, You hereby consent to the transfer of Your information to the United States and its collection, storage, sharing and use as described in this Privacy Policy.
      
      You further agree that all transactions relating to the Platform shall be deemed to have occurred in the United States.
      
      Individual restrictions on use of information:
      
      As a User, you have some rights related to the use of your specific information, as described below. Any requests related to such rights should be directed only to Support@ 10k-club.com
      
      Every User has the ability to see the data he personally provided to Us or to opt- out of receiving marketing communication, by submitting a request to Support® 10k-club.com.
       
      Individuals within the European Union or European Economic Area are entitled to additional choices regarding the information collected about them, as a result of the data privacy law commonly referred to as "GDPR", as follows:
      
      - Limit Use: User can request that we limit the use of your data by contacting Support@ 10k-club .com

      - Rectification: User that find inaccuracies in their information, have the ability to request that those inaccuracies be corrected by submitting a request to Support@ 10k-club .com
      
      - Removal: User can request that his data be removed from Our systems by contacting ___________. It is noted that due to legal restraints (for example, anti-money laundering laws) some data may not be immediately removed or anonymized. Such data will be removed after the minimal applicable data retention period.
      
      How do we protect Your Information:
    
      Generally, 10K Club uses reputable third-party data centers and similar service providers to store much of Your personally identifiable information. 10K Club together with such service providers, attempts to protect Your personally identifiable information by utilizing reasonable, but not less than industry Standard, security measures to prevent loss, misuse, or unauthorized access. 10K Club uses secure server software (SSL) for transactions through the Platform.
      
      In addition, 10K Club restricts access to personally identifiable information to its employees, contractors, service providers and agents who need to know that information in order to operate, develop or improve the Platform. All such individuals are bound by confidentiality obligations and may be subject to discipline, including termination and criminal prosecution, if they fail to meet these obligations.
      
      However, although 10K Club takes very significant steps to secure all Users information, no system can be completely secure and there is always a chance that Your information will not remain secure, and You should always take great care in handling and disclosing Your personally identifiable information.
      
      Special California Privacy Rights
      
      A California resident who has provided personal information to a business with whom he has established a business relationship is entitled to request information about whether the business has disclosed personal information to any third parties for the third parties’ direct marketing purposes. In general, if the business has made such a disclosure of personal information, upon receipt of a request by a California resident as aforesaid, the business is required to provide a list of all third parties to whom personal information was disclosed in the preceding calendar year, as well as a list of the categories of personal information that were disclosed. California Customers may request this information from 10K Club only by e-mail to Support@10k-club .com or by mailing a request to 10K Club Inc., at _______________.
      
      Information about children
      
      The Platform is not intended for use by children under the age of thirteen (13), and they are not permitted to use the Platform. Accordingly, we do not knowingly collect personally identifiable information from children under the age of thirteen (13). If anyone believes that We have inadvertently collected personally identifiable information from a child under the age of thirteen (13), please contact Us at 150 East 58th Street, Suite 2400 New York, NY 10155
      
      with all relevant information so that We can investigate the matter, and, if existing, We will delete such the information. 
      
      Third Party Links:
      
      The Platform may contain links to websites owned and operated by third parties. This 10K Club Privacy Policy applies only to information collected on or by our Platform. 10K Club can't and does not exercise privacy control over the sites which may be linked from the Platform as aforesaid.
      
      If You have any questions or concerns about this Privacy Policy or the privacy practices of 10K Club's Platform, You can contact Us at Support@ 10k-club.com.`;
      Alert.alert(
        'Privacy Policy',
        htmlstring,
        [
         // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
        //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
          {text: 'Approve', onPress: () => this.login() },
        ],
        { cancelable: false }
      );
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


      async user_login() {
        
        
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
            userNormalLogin,
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
                   
                        AsyncStorage.multiSet([
                        ["username", responseData.user_response.username],
                        ["password", this.state.password],
                        ["user_id", responseData.user_id],
                        ["user_notification_status", responseData.user_response.user_notification_status],
                        ["is_profile_updated", responseData.user_response.is_profile_updated],
                        ["member_username", responseData.user_response.member_username],
                        ]);
                        console.log("normal login true");
                        
                        // setTimeout(() => {
                        //     this.setState({ showPopup: false });
                        //         this.setState({ isLoading: false });
                                
                        //       }, 15000);
                        //this.closeActivityIndicator();
                        this.setState({
                            isLoading: false
                            });
                            setTimeout(() => {
                            // this.setState({ showPopup: false });
                          }, 100);
                          console.log("777777777 login true");
                         // this.setState({ showPopup: false });
                       // this.props.navigation.navigate('Profile');
                       //this.props.navigation.navigate('Tabs');
                       if(responseData.user_response.is_profile_updated == 1){
                        this.props.navigation.navigate('Registration');
                       }else{
                        // this.props.navigation.navigate('Home');
                        console.log("888888888 login true");
                        this.props.navigation.navigate('Tabs');
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
                    console.log(error);
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
                                LOGIN
                                </Text>
                        </View>
                        
                        </View>

                        <View style={styles.profileImage}>
                <Image source={require('../images/user-min.png')}
          style={{width: 100, height: 100, borderRadius: 100/2}} />
          {/* <Text style={styles.InstagramText}>
                                
                                Instagram
                                </Text> */}

                                {/* <Image style={styles.InstagramIcon}
                                source={require('../images/instra-icon-min.png')}> 
                            </Image> */}
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
                                    placeholder="Username"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#A7B8B7'
                                    keyboardType='email-address'
                                    returnKeyType='next'
                                    autoCorrect={false}
                                    onSubmitEditing={()=> this.refs.txtPassword.focus()}
                                    onChangeText={(text) => this.setState({username:text})}
                                />
                                <TextInput style={styles.input} 
                                    // placeholder="סיסמה"
                                    placeholder="Password"
                                    // placeholderTextColor='rgba(255,255,255,0.8)'
                                    placeholderTextColor='#A7B8B7'
                                    returnKeyType='go'
                                    secureTextEntry
                                    autoCorrect={false}
                                    ref={"txtPassword"}
                                    onChangeText={(text) => this.setState({password:text})}
                                />
<View style={{flexDirection:'row',alignItems:'center',justifyContent:'center',marginTop: -8,}}> 
<View>
<TouchableOpacity onPress={()=> this.how_it_works()}  borderColor="transparent" style={{alignItems:'center'}}>
                                
                                <Text style={{
    textAlign: 'center',
    color :'#020202',
    // fontWeight: 'bold',
    fontSize: 13,
    // paddingHorizontal:10,
    // fontFamily: "Montserrat-SemiBold",
}}>

                                How it works?
                                
                                {/* היכנס */}
                                </Text>
                                
                            </TouchableOpacity>
                            </View>
                            <Text style={{fontSize:20,fontWeight:'400'}}>  |  </Text>  
                            <View>
                            {/* Linking.openURL('https://www.instagram.com/accounts/password/reset' */}
                            <TouchableOpacity onPress={()=> this.props.navigation.navigate('ForgotPass') }  borderColor="transparent" style={{alignItems:'center'}}>
                                
                                <Text style={{
    textAlign: 'center',
    color :'#020202',
    fontSize: 13,
}}>

                                Forgot Password?
                                
                                </Text>
                                
                            </TouchableOpacity>
                            </View>
</View>

                                <TouchableOpacity onPress={()=> this.user_login()}  borderColor="transparent" style={{alignItems:'center'}}>
                                <LinearGradient start={{x: 0, y: 0}} end={{x: 0, y: 1}} colors={['#070909', '#070909', '#070909']} style={styles.buttonContainer}>
                                    <Text style={{
        textAlign: 'center',
        color :'#f2f2f3',
        // fontWeight: 'bold',
        fontSize: 19,
        // paddingHorizontal:10,
        // fontFamily: "Billabong",
	}}>
  
                                    Log in
                                    
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
            <DialogInput isDialogVisible={this.state.isDialogVisible}
            title={"Privacy Policy"}
            message={"test"}
            // hintInput ={"Password"}
            submitInput={ (inputText) => {this.sendInput(inputText,type=2,item.id,item.sent_primary_id)} }
            textInputProps={{autoCorrect:false }} 
            cancelText= {' '}  
            closeDialog={ () => {}}
          
            >
</DialogInput>
<View style={{marginHorizontal:20}}>
<Dialog
//style={{marginHorizontal:20}}
width={0.9}
    visible={this.state.visible}
    footer={
      <DialogFooter>
        <DialogButton
          text="CANCEL"
          // color="#000"
          // style={{color:"#000"}}
          textStyle = {{color:"#6e6e6e"}}
          onPress={() => { this.closeDialogBox()}}
        />
        <DialogButton
          text="AUTHORIZE"
          textStyle = {{color:"#6e6e6e"}}
          onPress={() => { this.setState({ visible: false }) }}
        />
      </DialogFooter>
    }
  >
    <DialogContent>
      <View style={{marginTop: 30}}>
      <Text style={{color:"#000"}}>{htmlstring_2}</Text>
      <View style={{flexDirection:'row',marginTop:30}}>
      <Text style={{color:"#000"}}>For more information please visit </Text>
      <TouchableOpacity onPress={()=> Linking.openURL('http://www.10k-club.com/privacy.pdf')}  borderColor="transparent" style={{alignItems:'center'}}>
                                
                                <Text style={{
    textAlign: 'center',
    color :'#0000EE',
    // fontWeight: 'bold',
    fontSize: 13,
    // paddingHorizontal:10,
    // fontFamily: "Montserrat-SemiBold",
    // textDecorationColor:'#0000EE',
    borderBottomWidth:1,
    borderBottomColor:'#0000EE',
}}>

                                Privacy Policy
                                
                                {/* היכנס */}
                                </Text>
                                
                            </TouchableOpacity>
                            </View>
      </View>
      
    </DialogContent>
  </Dialog>
  </View>
{/* <Modal style={styles.backdropStyle}
                    animationType={"slide"}
                    transparent={true}
                    animated={true}
                    // closeOnClick={true}
                    supportedOrientations={['portrait']}
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
                      <View
                        style={styles.modalStyle}>
                          <Text>{htmlstring_2}</Text>

                        </View>
  
</Modal> */}
            </View>
            
        )
    }
}

/*
const styles = StyleSheet.create({
    container: { 
        flex:1,
        width: '100%',
    height:'100%',
    backgroundColor:'#F7F8F8'
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'flex-start',
        flex: 1,
        top:20
    },
    logo: {
        width: 70,
        height: 100,
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
        // top: 0,
        // height: 200,   
        paddingHorizontal: 40,
        // flex:1
        // alignItems: 'center',    
      justifyContent: 'flex-start',
      flex: 1, 
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

    },
    text_register:{
      textAlign: 'center',
      color :'#ffffff',
      fontSize: 20,
      fontFamily:'Montserrat-SemiBold',
     marginTop: 10 
    },  
    profileImage: {
      alignItems: 'center',
      justifyContent: 'flex-start',
      // flex: 1,  
      
        // margin: 30,
        // paddingTop: ( Platform.OS === 'ios' ) ? 35 : 40,
        // marginLeft:10
    },
})

*/

const styles = StyleSheet.create({
  container: { 
    flex:1,
    width: '100%',
height:'100%',
backgroundColor:'#F2F1F1'
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
      fontSize: 14,  
      paddingHorizontal:10,
      top: 3,   
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
  
}
})