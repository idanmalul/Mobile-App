/*
Guideline Screen 1
*/
import React, { Component } from 'react'
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
//I18nManager.forceRTL(true); 
import LinearGradient from 'react-native-linear-gradient';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;


export default class GuidelineScreen_1 extends Component {
    constructor(props){
        super(props)
        this.state = {
            isLoading: this.props.isLoading, 
            showPopup: false,
            
        };

    }
   
      async componentWillMount() {
        try {
            BackHandler.addEventListener(
                "hardwareBackPress",
                this.handleBackPress
              );
              
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

    render() {
        return (
            <ImageBackground source={Images.guideline_screen_1}  resizeMode="stretch" style={styles.container}>
            <SafeAreaView style={styles.container}>
            
                
                <StatusBar barStyle="light-content" />
                {/* <KeyboardAvoidingView behavior='padding' style={styles.container}> */}
                    {/* <TouchableWithoutFeedback style={styles.container}  */}
                            {/* onPress={() => this.setState({ showPopup: false })}> */}
                            
                        
                        {/* <View style={styles.logoContainer}>
						
                            <View style={styles.logoContainer}>
                            

                               <Image style={styles.logo}
                                    // source={require('../images/logo.png')}> 
                                    source={require('../images/logo_login.png')}> 
                                </Image>
                                <Text style={styles.text_login}> Guideline Screen - 1</Text> 
                            </View>
                            
                            </View> */}
                            
                            {/* {
						this.state.isLoading &&
						<Spinner />
          }  */}
                            
                            <View style={styles.loginButtonContainer}>
                            {/* <Text style={styles.text_login}> LOG IN BY</Text>  */}
                            <TouchableOpacity 
                            onPress={() => this.props.navigation.navigate('GuidelineScreen_2')}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#ffffff00', '#ffffff00', '#ffffff00']} style={styles.loginButton}>
                            
                            
                            {/* <Image style={styles.ista_logo} 
                                    // source={require('../images/logo.png')}> 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image> */}
                                    <Text style={styles.buttonText}>
                                    {/* Login with instagram */}
                                    {/* כניסה דרך אינסטגרם */}
                                    Next>  
                                    </Text>
                                   
                                
                                </LinearGradient>
                                </TouchableOpacity>
                            </View> 
                            
                            
                    {/* </TouchableWithoutFeedback> */}
                {/* </KeyboardAvoidingView> */}
                
                
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
        color :'#ffffff',
        fontWeight: 'bold',
        fontSize: 18,
        paddingHorizontal:10,
        // fontFamily: "Billabong",
	},
	closeStyle: {
		marginTop: 22,
      },
      loginButtonContainer:{
        position: 'absolute',
        left: 0,
        right: 0,
        bottom: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 30 : 50) : 10,
        height: 150,
        padding: 15,
        marginHorizontal:30,
        alignItems:'center',
        justifyContent: 'flex-end',
        width:'85%'
        // flex:1,
      },
      loginButton: {
        //backgroundColor: 'transparent',
        paddingVertical: 15, 
        // borderRadius:100,
    // borderWidth: 1,
    // borderColor: '#fff',
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