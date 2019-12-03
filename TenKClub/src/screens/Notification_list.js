import React from 'react';
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
    FlatList,
    // AsyncStorage,
    BackHandler,
    I18nManager,
    alert,Alert

} from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { Images, Colors } from "../themes";
import { myNotificationList, updateUserFavourite } from "../constants/apis";
import Spinner from "../components/Spinner";
import _ from "lodash";
import LinearGradient from 'react-native-linear-gradient';
import { ifIphoneX } from "react-native-iphone-x-helper";
import FlatListComponent from '../components/FlatListComponent'
import { CheckBox } from 'react-native-elements'
import { ScrollView } from 'react-native-gesture-handler';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false);
const isRTL = I18nManager.isRTL;




export default class Notification_list extends React.Component {

  static navigationOptions = {
    title: '',  // Message
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon3.png')}/>;
    },
};
    constructor(props) {
        super(props);
        this.state = {
         data: [],
         user_profile_pic_url: ''
      };
      }

      async componentWillMount() {
        try {
            BackHandler.addEventListener(
                "hardwareBackPress",
                this.handleBackButtonClick
              );
              AsyncStorage.multiGet(['user_profile_pic_url']).then((data) => {
                let user_profile_pic_url = data[0][1];
                this.setState({
                  user_profile_pic_url : user_profile_pic_url
                })
              });
              this.getNotificationList();
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
      getNotificationList = ()=>{
        this.setState({ isLoading: true });
        // return false;
        AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
          let username = data[0][1];
          let password = data[1][1];
          let session_user_id = data[2][1];
          let formBody = "user_id="+session_user_id;
          if (username !== null && username !== ''){
              fetch(
                // 'http://192.168.1.23/10k-club/webservices/get_story_list.php',
                // 'http://10k.tempurl.co.il/webservices/get_story_list.php',
                myNotificationList,
                {
                  method:'POST',
                  headers: {
                    Authorization: "Bearer token",
                    "Content-Type": "application/x-www-form-urlencoded"
                    
                  },
                body: formBody,
              
              })
        
        .then((response)=> response.json())
        
        .then((res) => {
          //alert(JSON.stringify(res))
          if(res.status === 'true'){
            this.setState({ isLoading: false });
            this.setState({
              data:res.response
            });
           
          }
          
          else {
            this.setState({ isLoading: false });
            // alert('Something went wrong. Please try again.',res.message);
            // alert('No event available!');
            setTimeout(() => {
              Alert.alert(
                  'Alert',
                  'No Notifications available!',
                  [
                   // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
                  //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
                    {text: 'OK', onPress: () => this.setState({ isLoading: false })},
                  ],
                  { cancelable: false }
                )
            }, 100);
          }
        
        
        })
        
         .done();
      }
    });
        
        }

        componentWillUnmount() {
          BackHandler.removeEventListener(
              "hardwareBackPress",
              this.handleBackButtonClick
          );
      }
      handleBackButtonClick() {
          // this.props.navigation.goBack(null);
          // this.props.navigation.navigate('Home');
          return false;
  
      }
      renderSeparator = () => {
        return (
          <View
            style={{
              height: 0.5,
              width: "99%",
              backgroundColor: "#CED0CE",
              //marginLeft: "14%"
            }}
          />
        );
      };

      render() {
        return (
          <View style={styles.container}>
          <ImageBackground source={Images.screen_6} resizeMode="stretch" style={styles.container}>
          {/* <View style={styles.settingrow}>
                   <Image source={{uri: this.state.user_profile_pic_url}} style={styles.profileIconRight}/>
 
                   <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Settings')}>
                        
                        <Image source={Images.setting} style={styles.headerIconRight} />
                        
                        </TouchableOpacity>
                    
                   </View> */}
                   <View style={{flexDirection:'row'}}>
                <View style={{flex:0.5,...ifIphoneX({
            marginTop: 30,

        },{
            marginTop: 10,

        }),marginLeft:10}}>
                <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity>
                </View>
                <View style={styles.settingrow}>
                   <Image source={{uri: this.state.user_profile_pic_url}} style={styles.profileIconRight}/>
 
                   <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Settings')}>
                        
                        <Image source={Images.setting} style={styles.headerIconRight} />
                        
                        </TouchableOpacity>
                    
                   </View>
                </View>
                   <View style={styles.firstrow}>
                   <Text style={styles.title}>Notifications</Text>
                   </View>
          <View style={styles.mainview}>
          <FlatList 
      keyExtractor={this._keyExtractor}
      ItemSeparatorComponent={this.renderSeparator}
      data={this.state.data}
      renderItem={this.renderItem.bind(this)}/>
</View>
</ImageBackground>
            </View>
        );
      }
      renderItem(data) {
        let { item, index } = data;
        return (
          
          <View style={styles.itemBlock}>
            <Image  source={require('../images/icons/notification-message-icon.png')} style={styles.itemImage}/>
            <View style={styles.itemMeta}>
              <Text style={styles.itemName}>{item.notification_message}</Text>
              {/* <Text style={styles.itemLastMessage}>{item.last_message}</Text> */}
            </View>
          </View>
        ) 
      }
    }
    
    
    
    const styles = StyleSheet.create({
      container: {
        flex: 1,
        //flexDirection:'column', 
        // backgroundColor: '#1f2041',
        height: '100%', 
        width: '100%',
      },
      settingrow:{
        flex: 0.5,
        flexDirection:'row',
        ...ifIphoneX({
            marginTop: 30,

        },{
            marginTop: 10,

        }),
        justifyContent:'flex-end',
        marginHorizontal:30,
        alignItems:'flex-start'
    },
    headerIconRight: {
      
      textAlign : isRTL ? "left" : "right",
        width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
        height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
        //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
        marginTop: 20,
        // zIndex:1
    },

    profileIconRight: {
      textAlign : isRTL ? "left" : "right",
        width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
        height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
        //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
        marginTop: 20,
        width:30, 
        height:30,
        borderRadius:30/2,
        marginHorizontal: 10,
      
        // zIndex:1
    },
    firstrow: {
    
      // flex: 1,
      // justifyContent:'flex-start',
      flexDirection: isRTL ? 'row-reverse' : 'row',
      //backgroundColor: "#ffc857",
      borderBottomWidth:1.5,
      borderColor: "#727171",

      marginTop: 10,
      //marginHorizontal:17
  
    },
    title: {
      fontSize: 20,
      color: "#fff",
      marginHorizontal:10,
      paddingBottom:10,
      fontWeight: 'bold',
    },
      mainview:
      {
        flex:1,
        ...ifIphoneX({
          marginTop: 10,

      },{
          marginTop: 10,

      }),
      marginHorizontal:5,
      },
      itemBlock: {
        flexDirection: 'row',
        paddingBottom: 5,
        marginHorizontal:5,
      },
      itemImage: {
        width: 30,
        height: 30,
        //borderRadius: 25,
        //marginHorizontal:10,
        marginHorizontal:5,
        marginTop:10,
        paddingBottom:5

      },
      itemMeta: {
        marginLeft: 10,
        justifyContent: 'center',
      },
      itemName: {
        fontSize: 15,
        color: "#fff",
        marginTop:10,
        paddingBottom:5,
        marginHorizontal:5,
        //margin:8
      },
      itemLastMessage: {
        fontSize: 14,
        color: "#111",
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
            marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,
          },
          {
            // height: 55
            marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,
    
          }
        ),
        // alignItems:'flex-end'
    },
  icon: {
    width: 20,
    height: 26,
  },
    });