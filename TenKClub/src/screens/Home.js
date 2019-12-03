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
  Icon,
  I18nManager,
  VirtualizedList,
  PixelRatio,
  // AsyncStorage,
  BackHandler
} from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { ifIphoneX } from "react-native-iphone-x-helper";
import { Images, Colors } from "../themes";
import FlatListComponent from '../components/FlatListComponent';
import MadeForYouComponent from '../components/MadeForYouComponent';
import { getUserProfile } from "../constants/apis";
import Spinner from "../components/Spinner";

// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false); 
const isRTL = I18nManager.isRTL;

export default class Home extends Component {

  static navigationOptions = { 
    title: '',  // Main
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon1.png')}/>;
    },
};
  constructor(props){
    super(props)
    this.state = {
        // showPopup: false,
        isLoading: true,
        profileData: []
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
         profileData: res.user_details
        });
        
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

  async componentWillMount() {
      //alert(isRTL);
      this.getUserDetail();
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
      <ImageBackground source={Images.screen_6}  resizeMode="stretch" style={styles.container}>
      <View style={styles.firstrow}>
      {/* <View>
      <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity>
                    </View>  */}
                    
                    <View style = {{flex:1,flexDirection:'row'}}>
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
                    <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Settings')}
                        style={{ position: 'absolute', right: 10, }}>
                        <Image source={Images.setting} style={styles.headerIconRight} />
                    </TouchableOpacity>
      </View>
      <View style={styles.secondrow}>
            <FlatListComponent/>
      </View>
      {/* <View style={styles.thirdrow}></View> */}
      <View style={styles.fourthrow}>
      
      <MadeForYouComponent/>
        
      </View>
      {
						this.state.isLoading &&
						<Spinner />
          }
      </ImageBackground>
      </View>
    );
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

    // flex: 2.2,
    height:'22%',
    flexDirection: isRTL ? 'row-reverse' : 'row',
    //backgroundColor: "#ffc857",
    borderBottomWidth:0.5,
    borderColor: "#727171",

  },

  secondrow: {
    // flex: 4.5,
    // height:'55%',
    ...ifIphoneX(
      {
        // height: 50,
        // height:'52%',
        height:'30%',
      },
      {
        // height: 55
        // height:'55%', 
        height:'33%', 

      }
    ),
    //backgroundColor: "#4b3f72"
    borderBottomWidth:0.5,
    borderColor: "#727171",
  },

  thirdrow: {
    flex: 1.8,
    // backgroundColor: "#119da4"
    borderBottomWidth:0.5,
    borderColor: "#727171",
  },

  fourthrow: {
    // flex: 3,
    ...ifIphoneX(
      {
        // height: 50,
        // height:'31%',
        height:'53%',
      },
      {
        // height: 55
        // height:'25%',
        height:'47%',
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
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 95 : 110 : 100, 
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 95 : 110 : 100, 
      borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 95 : 110 : 100)/2,
    },
    {
      // height: 55
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 90, 
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 90, 
      borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 90)/2,

  
    }
  ),

  
  position:'absolute',
  zIndex:9999,
  left:0,
  top:0,
 // marginBottom:100
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
      marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 60 : 70 : 65,
      left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 65 : 75 : 60,
    },
    {
      // height: 55
      height:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 22 : 30 : 20,
      width:110,
      marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 60 : 70 : 60,
      left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 55 : 75 : 55,
  
    }
  ),
  },
  icon: {
    width: 25,
    height: 24,
  },
});