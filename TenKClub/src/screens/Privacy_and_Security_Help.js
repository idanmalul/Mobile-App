/*
Login Screen
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
import Pdf from 'react-native-pdf';
//import PDFView from 'react-native-view-pdf';
// I18nManager.forceRTL(true); 
const isRTL = I18nManager.isRTL;

const resources = {
    //file: Platform.OS === 'ios' ? 'downloadedDocument.pdf' : '/sdcard/Download/downloadedDocument.pdf',
    url: 'https://www.ets.org/Media/Tests/TOEFL/pdf/SampleQuestions.pdf',
   // base64: 'JVBERi0xLjMKJcfs...',
  };
export default class Privacy_and_Security_Help extends Component {
    constructor(props) {
        super(props)
        this.state = {
         isActive:false,
         isSwitch1On: false,
         

        }
        
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
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

    componentWillMount() {
        //alert(isRTL);
        //this.getUserDetail();
        BackHandler.addEventListener(
            "hardwareBackPress",
            this.handleBackButtonClick
        );
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
        const source = {uri:'http://www.10k-club.com/privacy.pdf',cache:true};
        const resourceType = 'url';

        return (

            
            <ImageBackground source={Images.screen_settings} resizeMode="stretch" style={styles.container}>
                {/* <SafeAreaView style={styles.container}> */}


                {/* <StatusBar barStyle="light-content" /> */}



                {/* <ScrollView> */}

                <View>

        <LinearGradient
          start={{ x: 0.5, y: 0.25 }}
          end={{ x: 0.5, y: 1.0 }}
          colors={[Colors.mainColor, Colors.colorStatusBar]}
          style={styles.headerView}
        >
          
          {/* <Text style={styles.headerTitle}>Personal Information</Text> */}
          <Text style={styles.headerTitle}>Privacy and Security Help</Text>
          <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        style={{ position: 'absolute', left: 10, }}>
                        <Image style={styles.headerIconRight} source={Images.chevron_left} />
                    </TouchableOpacity>
        </LinearGradient>
      </View>
                <View style={styles.container}>
                
                <Pdf
                    source={source}
                    onLoadComplete={(numberOfPages,filePath)=>{
                        console.log(`number of pages: ${numberOfPages}`);
                    }}
                    onPageChanged={(page,numberOfPages)=>{
                        console.log(`current page: ${page}`);
                    }}
                    onError={(error)=>{
                        console.log(error);
                    }}
                    style={styles.pdf}/>
      
                
            </View>

                

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
    pdf: {
        flex:1, 
        width:Dimensions.get('window').width,
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'flex-start',
        flex: 1,
        top: 28

    },
    logo: {
        width: 40,
        height: 43,
    },
    headerView: {
        justifyContent: "center",
        flexDirection: "row",
        alignItems: "center",
        ...ifIphoneX(
          {
            height: 50,
            marginTop: 33
          },
          {
            height: 55
          }
        ),
        backgroundColor: "#222327",
        elevation: 7,
        shadowOpacity: 7
      },
      headerIconRight: {
        width: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 25 : 40) : 25,
        height: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 25 : 40) : 25,
        //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
        marginTop: 10
      },
      headerTitle: {
        marginLeft: 10,
        fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
        color: Colors.colorWhite,
        fontWeight: "bold",
        marginTop: 10
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
        fontSize: 23,
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
        fontSize: 20,
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
        marginVertical: 40
    }

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