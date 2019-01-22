import React, { Component } from "react";
const ios_blue = "#43cea2";
const themeColor = '#0D1014'
import ReactTimeout from 'react-timeout';

let multipleData = [];
import _ from "lodash";
import {
  Platform,
  StyleSheet,
  Text,
  Dimensions,
  AsyncStorage,
  View,
  TextInput,
  TouchableOpacity,
  ScrollView,
  ToastAndroid,
  AlertIOS,
  Alert,
  KeyboardAvoidingView,
  ImageBackground,
  BackHandler,
  Modal,
  Image,
  I18nManager
 // BVLinearGradient
} from "react-native";
I18nManager.forceRTL(true); 
import { Images, Colors } from "../themes";
import { getUserProfile, updateUserProfile } from "../constants/apis";
import LinearGradient from "react-native-linear-gradient";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import { Dropdown } from 'react-native-material-dropdown';
import { SelectMultipleButton, SelectMultipleGroupButton } from 'react-native-selectmultiple-button';
const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
const isRTL = I18nManager.isRTL;
export default class Profile extends Component {

  
  static navigationOptions = {
    title: '',  // Search
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon-3.png')}/>; 
    },
};

  constructor(props) {
    super(props);
    this.state = {
      showPopup: false,
      isLoading: true,
      firstName: "",
      lastName: "",
      userID: "",
      Email: "",
      eventType: "",
      Age:"",
      genderLabel:"מין",
      genderArray: [{
        "label": "Male",
        value: 1,
    },{
      "label": "Female",
      value: 2,
  },{
    "label": "Other", 
    value: 3,
}],
      multipleSelectedData: [],
      multipleSelectedDataLimited: [],
      profileData: [],
      FavouritesData: [],
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    this.onChangeText = this.onChangeText.bind(this);
    this.eventTypeRef = this.updateRef.bind(this, 'eventType');

        
        
  }
  //---picker---/
  onChangeText(text) {
    ['eventType', 'code', 'sample', 'eventCat', 'privacy']
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
        
        for(let i=0;i<res.favourites.length; i++){
          multipleData.push(res.favourites[i].favourite_name);
        }
        let genderText = '';
        if(res.user_details.gender == 1){
          genderText = 'Male';
        } else if(res.user_details.gender == 1) {
          genderText = 'Female';
        }else{
          genderText = 'Not Specified';
        }
        for(let i=0;i<res.user_details.favourites.length; i++){
          this._singleTapMultipleSelectedButtons(res.user_details.favourites[i]);
          // multipleData.push(res.favourites[i].favourite_name);
        }
        
        this.setState({ 
          profileData: res.user_details,
          FavouritesData: res.favourites,
          firstName : res.user_details.first_name,
          lastName : res.user_details.last_name,
          userID : res.user_details.username,
          Email : res.user_details.email,
          user_id : res.user_details.user_id,
          Age :res.user_details.age,
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
  }
});
    
    }
    

  async doProfileUpdate() {
    
    this.setState({ isLoading: true });
    // let fcmToken = await AsyncStorage.getItem('fcmToken');
    let details = {
      
      first_name: this.state.firstName,
      last_name: this.state.lastName,
      user_name: this.state.userID,
      user_id: this.state.user_id,
      email: this.state.Email,
      gender: this.state.eventType,
      age: this.state.Age,
      favourites: this.state.multipleSelectedData,
      
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
        this.props.navigation.navigate("storyList");
        
      })
      .catch(error => {
        this.setState({ isLoading: false });
        console.log(error);
      });
  }
  
  //------exit app start------//
  componentWillMount() {
    //alert(isRTL);
    this.getUserDetail();
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
    /*
    AsyncStorage.multiGet(['username', 'password']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      
      if (username !== null && username !== ''){
        this.props.navigation.goBack(null);
        // this.props.navigation.navigate("Login");
        
        return false; 
          
      }else{
        this.props.navigation.navigate("Login");
        return true;
      }  
});  */
    
  }

  _singleTapRadioSelectedButtons(valueTap, gender) {
    this.setState({
      radioSelectedData: gender
    });
  }

  _singleTapMultipleSelectedButtons(interest) {
    if (this.state.multipleSelectedData.includes(interest)) {
      _.remove(this.state.multipleSelectedData, ele => {
        return ele === interest;
      });
    } else {
      this.state.multipleSelectedData.push(interest);
    }

    this.setState({
      multipleSelectedData: this.state.multipleSelectedData
    });
  }

  _singleTapMultipleSelectedButtons_limited(interest) {
    if (this.state.multipleSelectedDataLimited.includes(interest)) {
      _.remove(this.state.multipleSelectedDataLimited, ele => {
        return ele === interest;
      });
    } else {
      if (this.state.multipleSelectedDataLimited.length < 3)
        this.state.multipleSelectedDataLimited.push(interest);
    }

    this.setState({
      multipleSelectedDataLimited: this.state.multipleSelectedDataLimited
    });
  }
  //------exit app end------//

  render() {
    return (
      
      <ImageBackground source={Images.screen_2_3}  resizeMode="stretch" style={styles.container}>
      <View>

        <LinearGradient
          start={{ x: 0.5, y: 0.25 }}
          end={{ x: 0.5, y: 1.0 }}
          colors={[Colors.mainColor, Colors.colorStatusBar]}
          style={styles.headerView}
        >
          
          {/* <Text style={styles.headerTitle}>Personal Information</Text> */}
          <Text style={styles.headerTitle}>פרטים אישיים</Text>
          <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        style={{ position: 'absolute', right: 10, }}>
                        <Image style={styles.headerIconRight} />
                    </TouchableOpacity>
        </LinearGradient>
      </View>
      <ScrollView>
        <View>
        
        
          <View>
        <KeyboardAwareScrollView>
           <ScrollView>
             <View style={styles.bodyView}>
               
              <View style={{ marginTop: 20 }}>

                <View
                  style={{
                    marginLeft: 5,
                    marginRight: 5,
                    elevation: 7,
                    backgroundColor: "transparent",
                    shadowOpacity: 7,
                    paddingBottom: 20,
                    marginTop: 15
                  }}
                >
                  <View
                    style={{
                      marginLeft: 10,
                      marginRight: 10,
                      marginTop: 15
                    }}
                  >
                    <View
                      style={{
                        flexDirection: "row",
                        alignItems: "center"
                      }}
                    >
                      {/* <View
                        style={{
                          flex: 0.3,
                          alignItems: "center",
                          justifyContent: "center"
                        }}
                      >
                        <Text style={styles.lableText}>
                          Name
                          <Text style={styles.starText}>*</Text>
                        </Text>
                      </View> */}
                      <View style={styles.inoutView}>
                        <TextInput
                          style={styles.input}
                          placeholder="שם פרטי"
                          placeholderTextColor="#fff" 
                          keyboardType="default"
                          underlineColorAndroid="transparent"
                          onChangeText={text => this.setState({ firstName: text })}
                          value={this.state.firstName}
                          returnKeyType="next"
                        />
                      </View>
                      
                    </View>

                    <View
                      style={{
                        flexDirection: "row",
                        alignItems: "center"
                      }}
                    >
                      
                      <View style={styles.inoutView}>
                        <TextInput
                          style={styles.input}
                          placeholder="שם משפחה"
                          placeholderTextColor="#fff" 
                          keyboardType="default"
                          underlineColorAndroid="transparent"
                          onChangeText={text => this.setState({ lastName: text })}
                          value={this.state.lastName}
                          returnKeyType="next"
                        />
                      </View>
                    </View>

                    <View
                      style={{
                        flexDirection: "row",
                        alignItems: "center"
                      }}
                    >
                      
                      <View style={styles.inoutView}>
                        <TextInput
                          style={styles.input}
                          placeholder="תעודת זהות"
                          placeholderTextColor="#fff" 
                          keyboardType="default"
                          underlineColorAndroid="transparent"
                          onChangeText={text => this.setState({ userID: text })}
                          value={this.state.userID}
                          returnKeyType="next"
                          editable = {false}
                        />
                      </View>
                    </View>
                    <View style={styles.inputMain}>
                      {/* <View
                        style={{
                          flex: 0.3,
                          alignItems: "center",
                          justifyContent: "center"
                        }}
                      >
                        <Text style={styles.lableText}>
                          Email ID
                          <Text style={styles.starText}>*</Text>
                        </Text>
                      </View> */}
                      <View style={styles.inoutView}>
                        <TextInput
                          style={styles.input}
                          placeholder="דואר אלקטרוני"
                          placeholderTextColor="#fff" 
                          keyboardType="email-address"
                          underlineColorAndroid="transparent"
                          onChangeText={text => this.setState({ Email: text })}
                          value={this.state.Email}
                          returnKeyType="next"
                          editable = {false}
                        />
                      </View>
                    </View>
                    <View style={styles.inputMain}>
                    
                      <View style={{
                        flex: 0.5,
                        height: 45,
                        backgroundColor: "transparent",
                        marginTop: 15,
                        borderBottomColor: 'gray',
                     //   borderBottomWidth: 1,
                      }}>
                      <View style={{ marginLeft: 5, marginTop: -19}}>
                                    
                                    <Dropdown
                                        label={this.state.genderLabel}
                                        baseColor={Colors.colorWhite}
                                        itemColor={Colors.orangeColor}
                                        selectedItemColor={Colors.orangeColor}
                                        disabledItemColor={Colors.orangeColor}
                                        textColor={Colors.orangeColor}
                                        ref={this.eventTypeRef}
                                        value={this.state.eventType}
                                        onChangeText={this.onChangeText}
                                        data={this.state.genderArray}
                                        labelFontSize={1}
                                        
                                    />
                                </View>
                                </View>
                      <View style={{
                        flex: 0.5,
                        height: 45,
                        backgroundColor: "transparent",
                        marginTop: 15,
                        right:0,
                        borderBottomColor: 'gray',
                       borderBottomWidth: 1,
                       marginLeft:15,
                       marginBottom: 0
                      }}>
                        <TextInput
                          style={styles.input}
                          placeholder="גיל"
                          placeholderTextColor="#fff" 
                          maxLength={10}
                          keyboardType="number-pad"
                          underlineColorAndroid="transparent"
                          onChangeText={text => this.setState({ Age: text })}
                          value={this.state.Age}
                          returnKeyType="next"
                        />
                      </View>
                    </View>

                    
                    <View style={{
    marginTop: 15,
    flexDirection: "row",
    alignItems: "center",
    color:"#fff"
  }}>
                      <View
                        style={{
                          flex: 0.3,
                          alignItems: "center",
                          justifyContent: "center"
                        }}
                      >
                        <Text style={styles.lableText}>
                          {/* Favourites */}
                          העדפות
                          <Text style={styles.starText}></Text>
                        </Text>
                      </View>
                      
                    </View>
                        <Text>&nbsp;</Text>
                    {/* <Text style={{ color: ios_blue, marginLeft: 10 }}>
          I like {_.join(this.state.multipleSelectedData, ", ")}
        </Text> */}
        <View
          style={{
            flex:1,
            flexWrap: "wrap",
            flexDirection: "row",
            justifyContent: 'space-between',
            //width:40%
            
          }}
        >
      
          {multipleData.map(interest => (
            
            <SelectMultipleButton
            
              key={interest}
              buttonViewStyle={{
                borderRadius: 8,
                height: 40,
                width: '45%'
    
              }}
              textStyle={{
                fontSize: 15
              }}
              highLightStyle={{
                borderColor: "gray",
                backgroundColor: "transparent",
                textColor: "gray",
                borderTintColor: ios_blue,
                backgroundTintColor: ios_blue,
                textTintColor: "white"
              }}
              value={interest}
              selected={this.state.multipleSelectedData.includes(interest)}
              singleTap={valueTap =>
                this._singleTapMultipleSelectedButtons(interest)
              }
              textField="favourite_name"
              dataItemKey="favourite_id"
              data={this.state.FavouritesData}
            />
            
          
           ))} 
        </View>
  
                  </View>

                  <View
                    style={{
                      alignItems: "center",
                      justifyContent: "center",
                      flexDirection: "row",
                      marginTop: 35
                    }}
                  >
                    <TouchableOpacity
                      onPress={() => {
                        this.doProfileUpdate();
                      }}
                      style={{
                        alignItems: "center",
                        justifyContent: "center",
                        backgroundColor: "white",
                        height: 50,
                        width: 300,
                        borderRadius: 30
                      }}
                    >
                      <Text
                        style={{
                          fontSize:
                            Platform.OS === "ios"
                              ? aspectRatio > 1.6
                                ? 15
                                : 17
                              : 18,
                          color: "#000",
                          fontWeight: "bold"
                        }}
                      >
                        {/* Save */}
                        שמירה
                      </Text>
                    </TouchableOpacity>
                    
                  </View>
                </View>
              </View>
            </View>
          </ScrollView>
        </KeyboardAwareScrollView>
</View>
        
          {
						this.state.isLoading &&
						<Spinner />
          }
          
        </View>
        </ScrollView>
      </ImageBackground>
      
    );
  }

  
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    // backgroundColor: Colors.colorlightgray,
    width: '100%',
		height:'100%'
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
  lableText: {
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: "#fff",
    marginTop: 15,
    marginRight: 10
  },
  starText: {
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.orangeColor
  },
  headerIcon: {
    width: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 30 : 40) : 60,
    height: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 30 : 40) : 60,
    borderRadius: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 15 : 20) : 30,
    marginTop: 10
  },
  headerIconRight: {
    width: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 25 : 40) : 60,
    height: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 25 : 40) : 60,
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
  addText: {
    color: "#fff",
    fontSize: 13
  },
  inoutView: {
    flex: 1,
    height: 50,
    backgroundColor: "transparent",
    //borderRadius: 0,
   // borderWidth: 0,
  //  borderColor: "gray",
  marginTop: 15,
    borderBottomColor: 'gray',
    borderBottomWidth: 1,
    
  },
  input: {
    // marginLeft: 10,
    fontSize: 15,
    height: 50,
    color:"#fff",
    // flexDirection: isRTL ? "row-reverse" : "row",
    textAlign: 'right'
  },
  inoutViewMulti: {
    flex: 0.6,
    height: 80,
   backgroundColor: Colors.colorlightgray,
    borderRadius: 5,
    borderWidth: 0.5,
    borderColor: "gray",
    marginTop: 5,
    //border: 'none'
    
  },
  inputMulti: {
    marginLeft: 10,
    fontSize: 15,
    height: 80,
    marginTop: -10
  },
  inputMain: {
    marginTop: 15,
    flexDirection: isRTL ? "row-reverse" : "row",
    alignItems: "center",
    color:"#fff"
  },
  titleView: {
    alignItems: "center",
    justifyContent: "center",
    height: 55,
    backgroundColor: Colors.colorWhite,
    elevation: 7,
    shadowOpacity: 7
  },
  bodyView: {
    backgroundColor: "transparent"
  },
  title: {
    textAlign: "center",
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.orangeColor
  },
  icon: {
    width: 28,
    height: 28,
  },
  
});
