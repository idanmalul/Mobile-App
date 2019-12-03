/*
Registration Screen
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
    AlertIOS,Dimensions,ScrollView,I18nManager,Animated,UIManager
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
import { Dropdown } from 'react-native-material-dropdown';

// I18nManager.forceRTL(true); 
const isRTL = I18nManager.isRTL;
const { State: TextInputState } = TextInput;

export default class Registration extends Component {

  static navigationOptions = {
    title: '',  // Message
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon5.png')}/>;
    },
};
    constructor(props){
        super(props)
        this.state = {
            showPopup: false,
            isLoading: true,
            firstName: "",
            lastName: "",
            gender: "",
            Email: "",
            dob: "",
            countryCode: "",
            phoneNumber: "",
            address: "",
            postalCode: "",
            city: "",
            provision: "",
            country: "",
            userID: "",
            Age:"",
            genderLabel:"Gender",
            genderArray: [{
              "label": "Male",
              value: 1,
          },{
            "label": "Female",
            value: 2,
        },{
          "label": "Not Specified", 
          value: 3,
      }],
            profileData: [],
            shift: new Animated.Value(0),
            // date:""
          };

        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
        this.onChangeText = this.onChangeText.bind(this);
        this.eventTypeRef = this.updateRef.bind(this, 'gender');
    }
    //---picker---/
  onChangeText(text) {
    ['gender', 'code', 'sample', 'eventCat', 'privacy']
        .map((name) => ({ name, ref: this[name] }))
        .filter(({ ref }) => ref && ref.isFocused())
        .forEach(({ name, ref }) => {
            this.setState({ [name]: text });
        }); 
    ddlData = text
}
updateRef(name, ref) {
    this[name] = ref;
}
//---picker end---//
    
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
          //  alert(JSON.stringify(res.favourites[0].favourite_name));
            let genderText = '';
            if(res.user_details.gender == 1){
              genderText = 'Male';
            } else if(res.user_details.gender == 2) {
              genderText = 'Female';
            }else{
              genderText = 'Not Specified';
            }
            /*
            for(let i=0;i<res.favourites.length; i++){
              multipleData.push(res.favourites[i].favourite_name);
            }
            
            for(let i=0;i<res.user_details.favourites.length; i++){
              this._singleTapMultipleSelectedButtons(res.user_details.favourites[i]);
              // multipleData.push(res.favourites[i].favourite_name);
            }

            */
            
            this.setState({ 
             profileData: res.user_details,
            //  FavouritesData: res.favourites,
              firstName : res.user_details.first_name,
              lastName : res.user_details.last_name,
              userID : res.user_details.username,
              Email : res.user_details.email,
              user_id : res.user_details.user_id,
              Age :res.user_details.age,
              gender : genderText,
              dob : res.user_details.birthday,
              countryCode : res.user_details.country_code,
              phoneNumber : res.user_details.contact_no,
              address : res.user_details.address,
              postalCode : res.user_details.postal_code,
              city : res.user_details.city,
              provision : res.user_details.state,
              eventType : genderText
            });
            this.setState({ isLoading: false });
            // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
            //this.setState.multipleSelectedData.includes(res.user_details.favourites);
          }
          else {
            this.setState({ isLoading: false });
            alert('test test not work',res.message);
          }
        
        
        })
        
         .done();
      }else{
        this.setState({ isLoading: false });
      }
    });
        
        }
        
    
      async doProfileUpdate() {
        if (this.state.gender == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please select gender");
              },
              android: () => {
                // ToastAndroid.show("Please enter username", ToastAndroid.LONG, ToastAndroid.CENTER);
                ToastAndroid.showWithGravityAndOffset(
                    "Please select gender",
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
          if (this.state.dob == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please select death of birth");
              },
              android: () => {
                ToastAndroid.show("Please select death of birth", ToastAndroid.LONG, ToastAndroid.BOTTOM);
              }
            })();
            return false;
          }
          if (this.state.city == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please enter city");
              },
              android: () => {
                ToastAndroid.show("Please enter city", ToastAndroid.LONG, ToastAndroid.BOTTOM);
              }
            })();
            return false;
          }
          if (this.state.provision == "") {
            Platform.select({
              ios: () => {
                AlertIOS.alert("Please enter state");
              },
              android: () => {
                ToastAndroid.show("Please enter state", ToastAndroid.LONG, ToastAndroid.BOTTOM);
              }
            })();
            return false;
          }

          // if (this.state.address == "") {
          //   Platform.select({
          //     ios: () => {
          //       AlertIOS.alert("Please enter address");
          //     },
          //     android: () => {
          //       ToastAndroid.show("Please enter address", ToastAndroid.LONG, ToastAndroid.BOTTOM);
          //     }
          //   })();
          //   return false;
          // }
          
        this.setState({ isLoading: true });
        var guideline_flag = await AsyncStorage.multiGet(['is_profile_updated']).then((data) => {
          let is_profile_updated = data[0][1];
          return is_profile_updated;
        });
        // let fcmToken = await AsyncStorage.getItem('fcmToken');
        let details = {
          
          first_name: this.state.firstName,
          last_name: this.state.lastName,
          user_name: this.state.userID,
          user_id: this.state.user_id,
          email: this.state.Email,
          gender: this.state.gender,
          age: this.state.Age,
         // favourites: this.state.multipleSelectedData,

            birthday : this.state.dob,
            country_code : this.state.countryCode,
            phone_number : this.state.phoneNumber,
            address : this.state.address,
            postal_code : this.state.postalCode,
            city : this.state.city,
            provision : this.state.provision,
            
        };
        
        let formBody = [];
        for (let property in details) {
          let encodedKey = encodeURIComponent(property);
          let encodedValue = encodeURIComponent(details[property]);
          formBody.push(encodedKey + "=" + encodedValue);
        }
        formBody = formBody.join("&");
        console.log(formBody, "--------");
        // setTimeout(() => {
        //   this.setState({ isLoading: false });
        // }, 10000);
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
            this.setState({ isLoading: false });
            // this.props.navigation.navigate("Favourites");
            AsyncStorage.multiSet([
              ["is_profile_updated", '2']
              ]);
            if(guideline_flag == 1){
              this.props.navigation.navigate('GuidelineScreen_1');
             }else{
              this.props.navigation.navigate("Favourites");
              // this.props.navigation.navigate('Tabs');
             }

            
          })
          .catch(error => {
            this.setState({ isLoading: false });
            console.log(error);
          });
      }
    
    componentWillMount() {
      this.keyboardDidShowSub = Keyboard.addListener('keyboardDidShow', this.handleKeyboardDidShow);
    this.keyboardDidHideSub = Keyboard.addListener('keyboardDidHide', this.handleKeyboardDidHide);

      var date = new Date().getDate(); //Current Date
      var month = new Date().getMonth() + 1; //Current Month
      var year = new Date().getFullYear(); //Current Year
      console.log("today date = > "+date + '/' + month + '/' + year);
      this.setState({  
        //Setting the value of the date time
        dob: date + '/' + month + '/' + year
      });
        //alert(isRTL);
        this.getUserDetail();
        BackHandler.addEventListener(
          "hardwareBackPress",
          this.handleBackButtonClick
        );
      }
      componentWillUnmount() {
        this.keyboardDidShowSub.remove();
    this.keyboardDidHideSub.remove();

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
      const { shift } = this.state;
        return (
            <ImageBackground source={Images.screen_1_back}  resizeMode="stretch" style={styles.container}>
            {/* <SafeAreaView style={styles.container}> */}
            
                
                {/* <StatusBar barStyle="light-content" /> */}
                
                            
                        
                {/* <ScrollView> */}
              
                
                            <View style={styles.mainContainer} >
                            {/* <KeyboardAvoidingView enabled behavior="padding">                 */}
        <KeyboardAwareScrollView>
        
           <ScrollView style={{paddingBottom:50}} >
           <Animated.View style={[styles.container, { transform: [{translateY: shift}] }]}> 
           <View style={styles.logoContainer}>
						
                        {/* <View style={styles.logoContainer}> */}
                        

                           <Image style={styles.logo}
                                // source={require('../images/logo.png')}> 
                                source={require('../images/logo_login.png')}> 
                            </Image>
                                { this.state.profileData.is_profile_updated==2 && 
                                <Text style={styles.text_register}>PROFILE</Text> 
                                }

                                { this.state.profileData.is_profile_updated!=2 && 
                                <Text style={styles.text_register}>REGISTER</Text> 
                                }
                            
                            
                        {/* </View> */}
                        
                        </View>
             <View style={styles.bodyView}>
                {/* 1st field : start */}
                <View style={styles.inputContainer}>
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        GENDER
                        </Text>
                    </View>
                    
                    <View style={styles.textInputView}>
                    
                        {/* <TextInput
                        style={styles.input}
                        placeholder="Male / Female"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        keyboardType="default"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ gender: text })}
                        value={this.state.gender}
                        returnKeyType="next"
                        /> */}

<Dropdown
                                        label={this.state.genderLabel}
                                        baseColor={Colors.colorWhite}
                                        itemColor={Colors.orangeColor}
                                        selectedItemColor={Colors.orangeColor}
                                        disabledItemColor={Colors.orangeColor}
                                        textColor={Colors.colorWhite}
                                        ref={this.eventTypeRef}
                                        value={this.state.gender}
                                        onChangeText={this.onChangeText}
                                        data={this.state.genderArray}
                                        labelFontSize={1}
                                        // borderBottomWidth={0}
                                        style={{marginLeft: 5,
                                            // marginTop: -40,
                                            fontSize: 15,
                                            // height: 50,
                                            // color:"#fff",
                                            // position:'relative',
                                            // flexDirection: isRTL ? "row-reverse" : "row",
                                            textAlign: isRTL ? "right" : "left",}}
                                        dropdownOffset={ {top: 15, left: 0 }}
                                        // dropdownMargins={{ min: 8, max: 16 }}
                                        // dropdownPosition=''
                                        inputContainerStyle={{ borderBottomColor: 'transparent'}}
                                    />
                    </View>
                </View>
                {/* end */}
                {/* 2nd field : start */}
                <View style={styles.inputContainer}>
                    
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        DATE OF BIRTH
                        </Text>
                    </View>
                    <View style={styles.textInputView}>
                    
                    <DatePicker
        style={{width: '100%', alignItems:"flex-start"}}
        // style={styles.input}
        date={this.state.dob}
        mode="date"
        placeholder="DD/MM/YYYY"
        format="DD/MM/YYYY"
        // placeholderTextColor="#99b0bb" 
        placeholderStyle={styles.textboxfieldd}
        // minDate="2016-05-01"
        // maxDate="2016-06-01"
        confirmBtnText="Confirm"
        cancelBtnText="Cancel"
        customStyles={{
          dateIcon: {
            // position: 'absolute',
            // left: 0,
            // top: 4,
            // marginLeft: 0
            width:0,
            height:0,
          },
          dateInput: {
            
            // marginLeft: 36
            borderWidth: 0,
            borderColor:"#fff",
            marginTop:7,
            marginLeft: 5,
            fontSize: 15,
            height: 50,
            color:"#fff",
            // width: 500,
            // flexDirection: isRTL ? "row-reverse" : "row",
            alignItems: 'flex-start',
            textAlign: isRTL ? "right" : "left",
          },
          placeholderText: {
            fontSize: 15,
            color: '#99b0bb',
            // fontFamily: 'Montserrat-Regular'
        },
        dateText: {
            fontSize: 15,
            color: '#fff',
            // fontFamily: 'Montserrat-Regular'
        }
          // ... You can check the source to find the other keys.
        }}
        onDateChange={(date) => {this.setState({dob: date})}}
      />
                        {/* <TextInput
                        style={styles.input}
                        placeholder="dd / mm / yyyy"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        keyboardType="default"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ dob: text })}
                        value={this.state.dob}
                        returnKeyType="next"
                        /> */}
                    </View>
                </View>
                {/* end */}
                {/* 3rd field : start */}
                <View style={styles.inputContainer}>
                    
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        EMAIL
                        </Text>
                    </View>
                    <View style={styles.textInputView}>
                    
                        <TextInput
                        style={styles.input}
                        placeholder="Email Address"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        keyboardType="email-address"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ Email: text })}
                        value={this.state.Email}
                        returnKeyType="next"
                        editable = {false}
                        />
                    </View>
                </View>
                {/* end */}
                {/* 4th field : start */}
                <View style={styles.inputContainer}>
                    
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        PHONE NUMBER
                        </Text>
                    </View>
                    <View style={styles.textInputView}>
                    <View style={{flex:1, flexDirection: "row"}}>
                    <View style={isRTL ? styles.phoneNumberCountryCodeViewRTL : styles.phoneNumberCountryCodeViewLTR}>
                        <TextInput
                            style={isRTL ? styles.PhoneNumberInput : styles.input}
                            placeholder="+972"
                            placeholderTextColor="#99b0bb" 
                            placeholderStyle={styles.textboxfieldd}
                            maxLength={4}
                            keyboardType="default"
                            underlineColorAndroid="transparent"
                            onChangeText={text => this.setState({ countryCode: text })}
                            value={this.state.countryCode}
                            returnKeyType="next"
                            
                            />
                    </View>
                    <View style={isRTL ? styles.phoneNumberValueViewRTL : styles.phoneNumberValueViewLTR}>
                        <TextInput
                            style={isRTL ? styles.PhoneNumberInput : styles.input}
                            placeholder="Phone Number"
                            placeholderTextColor="#99b0bb" 
                            placeholderStyle={styles.textboxfieldd}
                            maxLength={15}
                            keyboardType="number-pad"
                            underlineColorAndroid="transparent"
                            onChangeText={text => this.setState({ phoneNumber: text })}
                            value={this.state.phoneNumber}
                            returnKeyType="next"
                            />
                    </View>
                    </View>
                        
                    </View>
                </View>

                {/* end */}
                {/* 5th field : start */}
                <View style={styles.inputContainer}>
                    
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        ADDRESS
                        </Text>
                    </View>
                    <View style={styles.textInputView}>
                    <View style={{flex:1, flexDirection: "row"}}>
                    <View style={isRTL ? styles.AddressViewRTL : styles.AddressViewLTR}>
                        <TextInput
                            style={isRTL ? styles.PhoneNumberInput : styles.input}
                            placeholder="Your street address"
                            placeholderTextColor="#99b0bb" 
                            placeholderStyle={styles.textboxfieldd}
                            // maxLength={4}
                            keyboardType="default"
                            underlineColorAndroid="transparent"
                            onChangeText={text => this.setState({ address: text })}
                            value={this.state.address}
                            returnKeyType="next"
                            
                            />
                    </View>
                    <View style={isRTL ? styles.postalCodeViewRTL : styles.postalCodeViewLTR}>
                        <TextInput
                            style={isRTL ? styles.PhoneNumberInput : styles.input}
                            placeholder="Postal Code"
                            placeholderTextColor="#99b0bb" 
                            placeholderStyle={styles.textboxfieldd}
                            // maxLength={7}
                            keyboardType="default"
                            underlineColorAndroid="transparent"
                            onChangeText={text => this.setState({ postalCode: text })}
                            value={this.state.postalCode}
                            returnKeyType="next"
                            />
                    </View>
                    </View>
                        
                    </View>
                </View>
                {/* end */}

                {/* 6th field : start */}
                <View style={styles.inputContainer}>
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        CITY
                        </Text>
                    </View>
                    
                    <View style={styles.textInputView}>
                    
                        <TextInput
                        style={styles.input}
                        placeholder="Enter your city here"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        keyboardType="default"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ city: text })}
                        value={this.state.city}
                        returnKeyType="next"
                        />
                    </View>
                </View>
                {/* end */}

                {/* 7th field : start */}
                <View style={styles.inputContainer}>
                    <View style={styles.lableTextView}>
                        <Text style={styles.lableText}>
                        STATE
                        </Text>
                    </View>
                    
                    <View style={styles.textInputView}>
                    
                        <TextInput
                        style={styles.input}
                        placeholder="Enter your state here"
                        placeholderTextColor="#99b0bb" 
                        placeholderStyle={styles.textboxfieldd}
                        // placeholderTextFontFamily= 'Montserrat-Regular'
                        keyboardType="default"
                        underlineColorAndroid="transparent"
                        onChangeText={text => this.setState({ provision: text })}
                        value={this.state.provision}
                        returnKeyType="next"
                        />
                    </View>
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
                                this.doProfileUpdate();
                                borderColor="transparent"
                              }}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#e0f9f5', '#61e1ce', '#22d5ba']} style={styles.createAccountButton}>
                            
                            
                            {/* <Image style={styles.ista_logo} 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image> */}
                                { this.state.profileData.is_profile_updated==2 && 
                                <Text style={styles.buttonText}>
                                SAVE
                                </Text>
                                }
                                { this.state.profileData.is_profile_updated!=2 && 
                                <Text style={styles.buttonText}>
                                CREATE ACCOUNT
                                </Text>
                                }
                                    
                                   
                                
                                </LinearGradient>
                                </TouchableOpacity>
                            </View>
                            
                {/* end */}

              
            </View>
            </Animated.View>
          </ScrollView>
          
        </KeyboardAwareScrollView>
        {/* </KeyboardAvoidingView> */}
        

        {
						this.state.isLoading &&
						<Spinner />
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

    handleKeyboardDidShow = (event) => {
      const { height: windowHeight } = Dimensions.get('window');
      const keyboardHeight = event.endCoordinates.height;
      const currentlyFocusedField = TextInputState.currentlyFocusedField();
      UIManager.measure(currentlyFocusedField, (originX, originY, width, height, pageX, pageY) => {
        const fieldHeight = height;
        const fieldTop = pageY;
        const gap = (windowHeight - keyboardHeight) - (fieldTop + fieldHeight);
        if (gap >= 0) {
          return;
        }
        Animated.timing(
          this.state.shift,
          {
            toValue: gap,
            duration: 1000,
            useNativeDriver: true,
          }
        ).start();
      });
    }
  
    handleKeyboardDidHide = () => {
      Animated.timing(
        this.state.shift,
        {
          toValue: 0,
          duration: 1000,
          useNativeDriver: true,
        }
      ).start();
    }
}
const styles = StyleSheet.create({

  AnimatedContainer: {
    backgroundColor: 'gray',
    flex: 1,
    height: '100%',
    justifyContent: 'space-around',
    left: 0,
    position: 'absolute',
    top: 0,
    width: '100%'
  },
    container: { 
        flex:1,
        width: '100%',
		height:'100%'
    },
    logoContainer: {
        alignItems: 'center',
        justifyContent: 'flex-start',
        flex: 1,
        top:15
    },
    logo: {
        width: 140,
        height: 143,
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
        color :'#ffffff',
        // fontWeight: 'bold',
        fontSize: 16,
        paddingHorizontal:10,
        // fontFamily: "Billabong",
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
      text_register:{
        textAlign: 'center',
        color :'#ffffff',
        fontSize: 20,
        fontFamily:'Montserrat-SemiBold',
       marginTop: 10 
      },
      bodyView: {
        flex:1,
        backgroundColor: "transparent"
      },
      inputContainer: {
        flex:1,
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
        color:"#fff",
        // flexDirection: isRTL ? "row-reverse" : "row",
        textAlign: isRTL ? "right" : "left",
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

      PhoneNumberInput: {
        marginRight: 15,
        fontSize: 15,
        height: 50,
        color:"#fff",
        // flexDirection: "row-reverse",
        textAlign:  "right",
      },
    //   phoneNumberMainView:{
    //     flex:1, flexDirection: "row" 
    //   },
      phoneNumberCountryCodeViewRTL:{
        flex:0.2,borderRightWidth:1, borderColor:"#34393b", flexDirection: isRTL ? "row-reverse" : "row" 
     },
     phoneNumberCountryCodeViewLTR:{
        flex:0.2,borderRightWidth:1, borderColor:"#34393b"
    },
     phoneNumberValueViewRTL:{
        flex:0.9,marginLeft:5, flexDirection: isRTL ? "row-reverse" : "row" 
     },
     phoneNumberValueViewLTR:{
        flex:0.9,marginLeft:5 
    },
    AddressViewRTL:{
        flex:0.6,borderRightWidth:1, borderColor:"#34393b", flexDirection: isRTL ? "row-reverse" : "row" 
     },
     AddressViewLTR:{
        flex:0.6,borderRightWidth:1, borderColor:"#34393b"
    },
    postalCodeViewRTL:{
        flex:0.4,marginLeft:5, flexDirection: isRTL ? "row-reverse" : "row" 
     },
     postalCodeViewLTR:{
        flex:0.4,marginLeft:5 
    },
    createAccountButtonView: {
        flex:1,
        marginTop:30,
        marginHorizontal: 30,
        marginBottom: 40
    },
    textboxfieldd:{
        fontFamily: 'Montserrat-Regular'
    },
    mainContainer: {
        marginVertical : 40
    },
    icon: {
      width: 25,
      height: 25,
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