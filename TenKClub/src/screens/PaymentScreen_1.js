import React, { Component } from 'react';
import {
  Platform,
  StyleSheet,
  Text,
  View,
  ImageBackground,
  Dimensions,
  TouchableOpacity,
  Image,
  // Icon,
  I18nManager,
  VirtualizedList,
  PixelRatio,
  // AsyncStorage,
  BackHandler,
  ScrollView,
  TextInput,
  TouchableHighlight,
  FlatList,
  Alert 

} from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { ifIphoneX } from "react-native-iphone-x-helper";
import { Images, Colors } from "../themes";
import FlatListComponent from '../components/FlatListComponent';
import MadeForYouComponent from '../components/MadeForYouComponent';
import { getUserProfile, updatePaymentMethod, PaymentReceivedList } from "../constants/apis";
import Spinner from "../components/Spinner";
import LinearGradient from 'react-native-linear-gradient';

// import MonthSelectorCalendar from 'react-native-month-selector';
import { Picker } from 'react-native-wheel-pick';
import DatePicker from 'react-native-datepicker';
import Icon from 'react-native-vector-icons/MaterialIcons'

const isIos = Platform.OS === 'ios'


// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false); 
const isRTL = I18nManager.isRTL;

export default class PaymentScreen_1 extends Component {

  static navigationOptions = {
    title: '', // My Event
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon4.png')}/>;
    },
};
  constructor(props){
    super(props)
    this.state = {
        // showPopup: false,
        isLoading: true,
        profileData: [],
        payment_data: [], // {'title':'Zara 1/2/19 120$'},{'title':'Alma 1/4/19 130$'},{'title':'Aldo 8/01/19 99$'},{'title':'Beauty care 11/6/19 150$'},{'title':'Cinema 27/09/19 200$'}
        month: '01/06/2018',
        user_id: '',
      };

    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    // this.onChangeText = this.onChangeText.bind(this);
    // this.eventTypeRef = this.updateRef.bind(this, 'gender');
}
  getUserDetail = ()=>{
    
  AsyncStorage.multiGet(['username', 'password']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      // alert(username);
      // return false;
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
// alert(res.user_details.profile_pic_url); 
        AsyncStorage.multiSet([
                        ["user_profile_pic_url", res.user_details.profile_pic_url]
                    ]);

                    
                  // return false;
              // let user_profile_pic_url = AsyncStorage.getItem('user_profile_pic_url');
      // alert(JSON.stringify(user_profile_pic_url)); return false;
        this.setState({ 
         profileData: res.user_details,
         user_id: res.user_details.user_id
        });
        this.setState({paypal_email: res.user_details.paypal_email})
        this.setState({ isLoading: false });
        // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
        //this.setState.multipleSelectedData.includes(res.user_details.favourites);
      }
      else {
        this.setState({ isLoading: false });
        alert('User not found!',res.message);
      }
    
    
    })
    .catch(error => {
        this.setState({ isLoading: false });
        console.log(error);
      })
    
     .done();
  }else{
    this.setState({ isLoading: false });
  }
  });
    
    }

    getPaymentReceivedList = ()=>{
    
      AsyncStorage.multiGet(['user_id']).then((data) => {
          let user_id = data[0][1];
          // alert(user_id);
          // let password = data[1][1];
          // alert(username);
          // return false;
          let formBody = "user_id="+user_id;
          // setTimeout(() => {
          //   this.setState({ isLoading: false });
          // }, 10000);
          if (user_id !== null && user_id !== ''){
            // alert("yes");
            
          fetch(
                // 'http://192.168.1.23/10k-club/webservices/get_user_profile.php',
                // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
                PaymentReceivedList,
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
          // alert(JSON.stringify(res)); 
          if(res.status === 'true'){
    // alert(JSON.stringify(res.response)); 
            // AsyncStorage.multiSet([
            //                 ["user_profile_pic_url", res.user_details.profile_pic_url]
            //             ]);
    
                        
                      // return false;
                  // let user_profile_pic_url = AsyncStorage.getItem('user_profile_pic_url');
          // alert(JSON.stringify(user_profile_pic_url)); return false;
            this.setState({ 
              payment_data: res.response,
            //  user_id: res.user_details.user_id
            });
            
            this.setState({ isLoading: false });
            // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
            //this.setState.multipleSelectedData.includes(res.user_details.favourites);
          }
          else {
            this.setState({ isLoading: false });
            // alert('User not found!',res.message);
          }
        
        
        })
        .catch(error => {
            this.setState({ isLoading: false });
            console.log(error);
          })
        
         .done();
      }else{
        this.setState({ isLoading: false });
      }
      });
        
        }

    async updatePaymentMethod() {
      if (this.state.paypal_email == "") {
          Platform.select({
            ios: () => {
              AlertIOS.alert("Please enter paypal email.");
            },
            android: () => {
              // ToastAndroid.show("Please enter username", ToastAndroid.LONG, ToastAndroid.CENTER);
              ToastAndroid.showWithGravityAndOffset(
                  "Please enter paypal email.",
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
        // if (this.state.dob == "") {
        //   Platform.select({
        //     ios: () => {
        //       AlertIOS.alert("Please select death of birth");
        //     },
        //     android: () => {
        //       ToastAndroid.show("Please select death of birth", ToastAndroid.LONG, ToastAndroid.BOTTOM);
        //     }
        //   })();
        //   return false;
        // }
        // if (this.state.city == "") {
        //   Platform.select({
        //     ios: () => {
        //       AlertIOS.alert("Please enter city");
        //     },
        //     android: () => {
        //       ToastAndroid.show("Please enter city", ToastAndroid.LONG, ToastAndroid.BOTTOM);
        //     }
        //   })();
        //   return false;
        // }
        // if (this.state.provision == "") {
        //   Platform.select({
        //     ios: () => {
        //       AlertIOS.alert("Please enter state");
        //     },
        //     android: () => {
        //       ToastAndroid.show("Please enter state", ToastAndroid.LONG, ToastAndroid.BOTTOM);
        //     }
        //   })();
        //   return false;
        // }

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
      // var guideline_flag = await AsyncStorage.multiGet(['is_profile_updated']).then((data) => {
      //   let is_profile_updated = data[0][1];
      //   return is_profile_updated;
      // });
      // let fcmToken = await AsyncStorage.getItem('fcmToken');
      let details = {
        paypal_email: this.state.paypal_email,
        user_id: this.state.user_id,
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
        updatePaymentMethod,
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
          
          // this.setState({ isLoading: false });
          // this.props.navigation.navigate("Favourites");
          // AsyncStorage.multiSet([
          //   ["is_profile_updated", '2']
          //   ]);
          // if(guideline_flag == 1){
          //   this.props.navigation.navigate('GuidelineScreen_1');
          //  }else{
          //   this.props.navigation.navigate("Favourites");
          //   // this.props.navigation.navigate('Tabs');
          //  }
          //  this.props.navigation.navigate("Favourites");

          
        })
        .catch(error => {
          this.setState({ isLoading: false });
          console.log(error);
        }).done();
    }

  async componentWillMount() {
      //alert(isRTL);
      this.getUserDetail();
      this.getPaymentReceivedList();
      BackHandler.addEventListener(
        "hardwareBackPress",
        this.handleBackPress
      );
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
      <View style={styles.container}>
      {/* <ImageBackground source={Images.screen_6}  resizeMode="stretch" style={styles.container}> */}
      <LinearGradient start={{x: 0, y: 0 }} end={{x: 1, y: 0}} colors={['#cccccc', '#a9a9a9', '#858585']} style={styles.container}>
        <ScrollView>
      <View style={styles.firstrow}>
      {/* <View>
      <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity>
                    </View>  */}
                    
                    <View style = {{flex:1,flexDirection:'row',alignItems:'center',justifyContent:'center',marginBottom:20,marginLeft:50 }}>
                    <View style={styles.profileImage}>
                    <View style={{flex:1}}>
                    <Image source={{uri : this.state.profileData.profile_pic_url}}
              style={styles.profile_image_circle} />
                <View style={styles.dajboardView}><Text style={{color:'#fff',marginLeft:25}}>Beginner</Text></View>
              </View>
              
                    </View>
                    <View style={styles.infoContainer}>
                    <Text style={styles.userFullName}>{this.state.profileData.full_name}</Text>
                    {/* <Text style={styles.followerCount}>15.2K followers | Indore, India</Text> */}
                    <View style={{flexDirection:'row'}}>
                    <Image source={Images.user_icon} style={{width: 12, height: 12}}
               />
                    <Text style={styles.followerCount}>  {this.state.profileData.follower_count} followers | {this.state.profileData.city}, {this.state.profileData.state}</Text>
                    </View>
                    
                    </View>
                    </View>  
                    {/* <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Settings')}
                        style={{ position: 'absolute', right: 10, }}>
                        <Image source={Images.setting} style={styles.headerIconRight} />
                    </TouchableOpacity> */}
      </View>
      <View style={styles.secondrow}>
            {/* <FlatListComponent/> */}
            <View style={{flex:1,backgroundColor:'#eaeaea',marginHorizontal:17,borderBottomLeftRadius:20,borderTopRightRadius:20,marginBottom:10, flexDirection:'row'  }}> 
            {/* <View >  */}
            <View style={styles.paypalLogoContainer}>
            <Image style={{width:75,height:18}}
                                // source={require('../images/logo.png')}> 
                                source={require('../images/PayPal.png')}> 
                            </Image>
            </View>
            <View style={styles.paypalContainer}>
            <View style={styles.inputContainer}>
              <View style={{backgroundColor:'#D1D1D1',height:35,width:35,justifyContent: 'center',
    alignItems: 'center'}}>
              <Image style={styles.inputIcon} source={require('../images/icons/user-icon.png')}/>
              </View>
          
          <TextInput style={styles.inputs}
              placeholder="Email"
              keyboardType="email-address"
              underlineColorAndroid='transparent'
              onChangeText={(paypal_email) => this.setState({paypal_email})}
              // onChangeText={text => this.setState({ paypal_email: text })}
              value={this.state.paypal_email}
                        />
        </View>
        
        {/* <View style={styles.inputContainer}>
        <View style={{backgroundColor:'#D1D1D1',height:35,width:35,justifyContent: 'center',
    alignItems: 'center'}}>
          <Image style={styles.inputIcon} source={require('../images/icons/lock-icon.png')}/>
          </View>
          <TextInput style={styles.inputs}
              placeholder="Password"
              secureTextEntry={true}
              underlineColorAndroid='transparent'
              onChangeText={(password) => this.setState({password})}/>
        </View> */}

        <TouchableHighlight style={[styles.buttonContainer, styles.loginButton]} onPress={() => this.updatePaymentMethod()}>
          <Text style={styles.loginText}>ADD PAYMENT METHOD</Text>
        </TouchableHighlight>
        </View>

        {/* </View> */}
            </View>
      </View>
      <View style={styles.thirdrow}>
      <View style={{flex:1,backgroundColor:'#eaeaea',marginHorizontal:17,borderBottomLeftRadius:20,borderTopRightRadius:20,marginBottom:10 }}> 
<View style={{marginVertical: 25,marginHorizontal:15  }}>
<Text style={{fontSize:20,color:'#000000'}}>ACCOUNT BALANCE</Text>
<View style={{flex:1, flexDirection:'row'}}>
<Text style={{fontSize:30,color:'#000000',fontWeight:'bold'}}>{(this.state.profileData.account_balance) ? this.state.profileData.account_balance : 0}</Text><Text style={{fontSize:18,color:'#000000',marginTop:3,fontWeight:'bold'}}>$</Text>
</View>
</View>

            </View>
      </View>
      <View style={styles.fourthrow}>
      <View style={{flex:1,backgroundColor:'#ffffff',marginHorizontal:17,borderBottomLeftRadius:20,borderTopRightRadius:20,marginBottom:20,flexDirection:'row',    }}> 
      <View style={{marginVertical: 15,marginHorizontal:15,flex:0.8  }}>
<Text style={{color:'#1077BE',marginBottom:5,fontSize:16,fontWeight:'bold'}}>Payments received</Text>

<FlatList 
      keyExtractor={this._keyExtractor}
      ItemSeparatorComponent={this.renderSeparator}
      data={this.state.payment_data}
      renderItem={this.renderItem.bind(this)}/>

{/* <Picker
  style={{ backgroundColor: 'white', width: 300, height: 215 }}
  selectedValue='item4'
  pickerData={['item1', 'item2', 'item3', 'item4', 'item5', 'item6', 'item7']}
  onValueChange={value => { }}
  itemSpace={30} // this only support in android
/> */}



</View>

{/* <View style={{flex:0.5}}>
<DatePicker
        style={{width: '100%', alignItems:"flex-start",backgroundColor:'#EAEAEA',borderTopRightRadius:7}}
        // style={styles.input}
        date={this.state.month}
        mode="date"
        placeholder="DD/MM/YYYY"
        format="MMMM YYYY"
        // placeholderTextColor="#99b0bb" 
        placeholderStyle={styles.textboxfieldd}
        // minDate="2016-05-01"
        // maxDate="2016-06-01"
        confirmBtnText="Confirm"
        cancelBtnText="Cancel"
        // iconSource={'<<<<<'}
        iconComponent={
          <Icon 
          size={25}
          color='#333333' 
          name='keyboard-arrow-down' 
          style={{left:-25,marginTop:30,position:'absolute',backgroundColor:'#EAEAEA',height:40,borderBottomLeftRadius:7}}
          /> 
       }
        customStyles={{
          dateIcon: {
            // position: 'absolute',
            // left: 0,
            // top: 4,
            // marginLeft: 0
            width:0,
            height:0,
            // backgroundColor:'#EAEAEA',
            // uri:require('../images/logo_login.png')
          },
          dateInput: {
            // borderBottomLeftRadius:7,borderTopRightRadius:7,
            // backgroundColor:'#EAEAEA',
            // marginLeft: 36
            borderWidth: 0,
            borderColor:"#000",
            marginTop:7,
            marginLeft: 0,
            fontSize: 15,
            height: 30,
            color:"#000",
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
            color: '#000',
            // fontFamily: 'Montserrat-Regular'
        }
          // ... You can check the source to find the other keys.
        }}
        onDateChange={(date) => {this.setState({month: date})}}
      />
</View> */}

            </View>
            
      {/* <MadeForYouComponent/> */}
        
      </View>
      </ScrollView>
      {
						this.state.isLoading &&
						<Spinner />
          }
          </LinearGradient>
      {/* </ImageBackground> */}
      </View>
    );
  }

  renderItem(data) {
    let { item, index } = data;
    return (
      
      <View style={{marginBottom:5}}>
        {/* <Image  source={require('../images/icons/notification-message-icon.png')} style={styles.itemImage}/> */}
        <View style = {{color:"#505050"}}>
          <Text >{item.full_name+"   "+item.payment_date+"   "+item.amount+""+((item.currency=='USD')? '$' : item.currency )}</Text>
          {/* <Text style={styles.itemLastMessage}>{item.last_message}</Text> */}
        </View>
      </View>
    ) 
  }
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
   // backgroundColor: '#1f2041',
   height: null, 
   width: null, 
  },

  firstrow: {
    flex: 1, 
    // flex: 2.2,
    // height:'22%',
    flexDirection: isRTL ? 'row-reverse' : 'row',
    //backgroundColor: "#ffc857",
    // borderBottomWidth:0.5,
    borderColor: "#727171",
    // marginBottom: 35

  },

  secondrow: {
    flex: 1,
    // flex: 4.5,
    // height:'55%',
    ...ifIphoneX(
      {
        // height: 50,
        // height:'52%',
        // height:'18%',
      },
      {
        // height: 55
        // height:'55%', 
        // height:'21%', 

      }
    ),
    //backgroundColor: "#4b3f72"
    // borderBottomWidth:0.5,
    borderColor: "#727171",
  },

  thirdrow: {
    flex: 1,
    // flex: 1.8,
    // backgroundColor: "#119da4"
    ...ifIphoneX(
      {
        // height: 50,
        // height:'52%',
        // height:'18%',
      },
      {
        // height: 55
        // height:'55%', 
        // height:'21%', 

      }
    ),
    // borderBottomWidth:0.5,
    borderColor: "#727171",
  },

  fourthrow: {
    flex: 1,
    
    ...ifIphoneX(
      {
        // height: 50,
        // height:'31%',
        // height:'53%',
      },
      {
        // height: 55
        // height:'25%',
        // height:'47%',
      }
    ),
    // backgroundColor: "#19647e"
    // borderBottomWidth:1,
    // borderColor: "#fff" 
     
  },
  headerIcon: {
    // textAlign : 'left',
    // marginLeft: 15,
    width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
    height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
    borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,

    ...ifIphoneX(
      {
        // height: 50,
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 10,
      },
      {
        // height: 55
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,

      }
    )
},
headerIconRight: {
  // textAlign : 'left',
    width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 25,
    height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 25,
    //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
    // marginTop: 40
    // marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10, 

    ...ifIphoneX(
      {
        // height: 50,
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 10,
      },
      {
        // height: 55
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,

      }
    )
},
profileImage: {
  flex: 0.3,
  justifyContent: 'center',
  alignItems: isRTL ? 'flex-start' : 'flex-end',
    margin: 5,
    // marginTop:40,
    marginLeft: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 0 : 30, 
    paddingTop: ( Platform.OS === 'ios' ) ? 20 : 0,

    ...ifIphoneX(
      {
        // height: 50,
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 30, 
      },
      {
        // height: 55
        marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? (PixelRatio.get() === 2 ? 13 : 20) : 40 : 30, 

      }
    )
},
infoContainer: {
flex:0.7,
justifyContent: 'center',
alignItems: isRTL ? 'flex-end' : 'flex-start',
margin: 5,
// marginTop:20,
marginLeft: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 0 : 0 : 0, 
paddingTop: ( Platform.OS === 'ios' ) ? 3 : 0,

...ifIphoneX(
  {
    // height: 50,
    marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 30, 
  },
  {
    // height: 55
    marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 0 : 20 : 10, 

  }
)
// marginTop:10
},
userFullName: {
  color: "#fff",
  fontSize: 18
},
followerCount: {
  color: "#fff",
  fontSize: 11,
  
},

profile_image_circle:{
  ...ifIphoneX(
    {
      // height: 50,
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100, 
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100, 
      borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100)/2,
    },
    {
      // height: 55
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100, 
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100, 
      borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100)/2,

  
    }
  ),

  
  position:'absolute',
  zIndex:9999,
  left:0,
  top:0,
  // marginBottom: 35
  
},

dajboardView:{
  backgroundColor:'#E56785',
  paddingHorizontal:13,
  paddingVertical:0,
  
  alignItems:'center',
  justifyContent:'center',
  borderRadius:8,
  fontFamily: 'Arial-Regular',

  ...ifIphoneX(
    {
      // height: 50,
      height:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 30 : 20,
      width:120,
      marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 75 : 70 : 65,
      left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 75 : 75 : 60,
    },
    {
      // height: 55
      height:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 22 : 30 : 20,
      width:110,
      marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 60 : 70 : 65,
      left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 55 : 75 : 60,
  
    }
  ),
  }

  ,
  paypalLogoContainer: {
    flex: 0.3,
    justifyContent: 'flex-start',
    alignItems: 'center',
    top:18,
    // backgroundColor: '#DCDCDC',
    // marginTop: 10,
    // marginRight: 30,
    marginLeft:15
  },
  paypalContainer: {
    flex: 0.9,
    justifyContent: 'center',
    alignItems: 'center',
    top:15
    // backgroundColor: '#DCDCDC',
    // marginTop: 10,
    // marginRight: 30 
    ,marginBottom:20
  },
  inputContainer: {
      borderBottomColor: '#F5FCFF',
      backgroundColor: '#FBFBFB',
      // borderRadius:30,
      borderBottomWidth: 1,
      width:200,
      height:35,
      marginBottom:15,
      flexDirection: 'row',
      alignItems:'center'
  },
  inputs:{
      height:35,
      marginLeft:5,
      borderBottomColor: '#FFFFFF',
      flex:1,
  },
  inputIcon:{
    width:10 ,
    height:14 ,  
    // marginLeft:15,
    // justifyContent: 'center',
    // alignItems: 'center',
    
    // backgroundColor: '#000000'
  },
  buttonContainer: {
    height:30,
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom:20,
    width:200,
    borderRadius:30,
  },
  loginButton: {
    backgroundColor: "#019EE3", 
  },
  loginText: {
    color: 'white',
  },
  // textInputView: {
  //   flex: 1,
  //   height: 50,
  //   backgroundColor: "transparent",
  //   //borderRadius: 0,
  //  // borderWidth: 0,
  // //  borderColor: "gray",
  //   marginTop: 5,
  //   borderColor: '#34393b',
  //   borderWidth: 1,
  //   // marginLeft: 20,
  //   // marginHorizontal: 20
    
  // },
  textboxfieldd:{
    fontFamily: 'Montserrat-Regular'
},
  icon: {
    width: 28,
    height: 24,
  },
});