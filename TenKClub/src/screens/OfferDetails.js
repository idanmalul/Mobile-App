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
    ToastAndroid,
    AlertIOS,
    Alert,
    StatusBar,
    TouchableWithoutFeedback,
    Linking
} from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { Images, Colors } from "../themes";
import { uploadEvent, getOfferDetailById } from "../constants/apis";
import Spinner from "../components/Spinner";
import _ from "lodash";
import LinearGradient from 'react-native-linear-gradient';
import { ifIphoneX } from "react-native-iphone-x-helper";
import FlatListComponent from '../components/FlatListComponent'
import { CheckBox } from 'react-native-elements'
import { ScrollView } from 'react-native-gesture-handler';
import Video from 'react-native-video';

import DialogInput from 'react-native-dialog-input';


// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false);
const isRTL = I18nManager.isRTL;
let noti  = false;

const shows_first = 
    {
        key: 1,
        name:'Suits',
        image: 'https://static.tvmaze.com/uploads/images/medium_portrait/0/2432.jpg',
        approved_users:[
            {
                key: 21,
                name:'Modern Family',
                image: 'https://static.tvmaze.com/uploads/images/medium_portrait/0/628.jpg'
            },
            {
                key: 32,
                name:'The Flash',
                image: 'https://static.tvmaze.com/uploads/images/medium_portrait/78/195988.jpg'
            },
            {
                key: 41,
                name:'Supergirl',
                image: 'https://static.tvmaze.com/uploads/images/medium_portrait/83/209955.jpg'
            }
        ],
        approved_users_count:'USERS CHECKED IN'
    }

export default class OfferDetails extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showPopup: false,
            isLoading: false,
            userData: [{
                "profile_pic_url": "https://instagram.fbho2-1.fna.fbcdn.net/vp/79d264c600d43cd574aedab640683cb9/5D0E47E1/t51.2885-19/s320x320/28764336_2135905183312804_4812961143834279936_n.jpg?_nc_ht=instagram.fbho2-1.fna.fbcdn.net",
                "full_name": "proxima@appdividend.com",
                "follower_count": "fafksakfakfa"
            }],
            FavouritesData: [],
            multipleSelectedData: [],
            checked:false,
            user_profile_pic_url: '',
            story_description: '',
            campaign_description: '',
            isDialogVisible: false,
            notification_flag: false

        };

        //this.handleBackButtonClick = this.handleBackButtonClick.bind(this);

    }
     setNotificationFlag = async (value) => {
      // alert(value);
      return this.setState({ notification_flag: value });
    };
    showDialog = (value) => {
      // alert(value);
      this.setState({ isDialogVisible: value });
    };
    sendInput = (value,type,story_id,sent_primary_id) => {

      if(!value){
        Platform.select({
          ios: () => {
            AlertIOS.alert("Please enter password.");
          },
          android: () => {
            ToastAndroid.show("Please enter password.", ToastAndroid.LONG, ToastAndroid.BOTTOM);
          }
        })();
        return false;
       }
       this.showDialog(false);
      // alert("P="+value+" type =  "+type +" story = "+story_id +" sent_primary_id = "+sent_primary_id);  
      // this.setState({ verificationCode: value });
      this.acceptStory(story_id,sent_primary_id,value,type);
    };
    alertOnPressStory(){
      this.setState({ isLoading: false });
      this.props.navigation.navigate("Home");
    }
    wrongPasswordRedirection(){
      this.setState({ isLoading: false });
      Linking.openURL('https://10k-club.com/registration');
      
    }
   async acceptStory(storyID,sent_primary_id,new_password,type) {
    // alert(storyID+" <===> "+sent_primary_id);
     if(!this.state.checked){
      Platform.select({
        ios: () => {
          AlertIOS.alert("Please select the checkbox.");
        },
        android: () => {
          ToastAndroid.show("Please select the checkbox.", ToastAndroid.LONG, ToastAndroid.BOTTOM);
        }
      })();
      return false;
     }
     
    //  alert("yes");
    //     return false;
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
      let formBody = "user_id="+session_user_id+"&story_id="+storyID+"&sent_primary_id="+sent_primary_id+"&user_new_pass="+new_password+"&type="+type+"";
      console.log(formBody);
      console.log(uploadEvent+'?'+formBody);
      // alert("top");
      if (username !== null && username !== ''){
        // alert(username);
        // return false;
        // setTimeout(() => {
        //   this.setState({ isLoading: false });
        // }, 15000);
        fetch(
            // 'http://192.168.1.23/10k-club/webservices/second.php',
            // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
            uploadEvent,
            {
              method:'POST',
              headers: {
                Authorization: "Bearer token",
                "Content-Type": "application/x-www-form-urlencoded"
              },
            body: formBody,
          
          })
    
    .then(response => response.json())
      .then(res => {
        console.log(res);
        if(res.status === 'true'){
          // this.showDialog(false);
          console.log("Data true");
          // this.setState({ isLoading: false });
          this.setState({ isUploaded: true });
         // alert("Story uploaded successfully!");
         Alert.alert(
          'Alert',
          res.message,
          [
           // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
          //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
            {text: 'OK', onPress: () => this.alertOnPressStory()},
          ],
          { cancelable: false }
        )
        }
        else {
          console.log("Data false");
          if(res.session == 'false'){
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
                     this.props.navigation.navigate('Login');
                     //this.props.navigation.navigate('Home');
                      // this.props.navigation.navigate('storyList');
                  }
                      
              });
          }
          // this.setState({ isLoading: false });
          // this.setState({ isLoading: false });
         // alert('Something went wrong. Please try again.',res.message);
         if(res.flag == 'wrong_password'){
          this.showDialog(true);

          // Alert.alert(
          //   'Alert',
          //   res.message,
          //   [
          //    // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
          //   //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
          //     {text: 'OK', onPress: () => this.wrongPasswordRedirection()},
          //   ],
          //   { cancelable: false }
          // )
         } else if (res.ig_response.two_factor_required == true){
          // this.showDialog(true);
          // alert("Two factor enabled!");
          AsyncStorage.multiSet([
            // ["username", responseData.user_response.username],
            // ["password", this.state.password],
            ["challenge_user_id", res.user_id],
            
            ])
            this.setState({
              isLoading: false
              })
            //   setTimeout(() => {
            //   this.setState({ showPopup: false });
            // }, 100);
            this.props.navigation.navigate('twoFactorOTPVerification');
          
        }else{
          Alert.alert(
            'Alert',
            res.message,
            [
             // {text: 'Ask me later', onPress: () => console.log('Ask me later pressed')},
            //  {text: 'Cancel', onPress: () => console.log('Cancel Pressed'), style: 'cancel'},
              {text: 'OK', onPress: () => this.setState({ isLoading: false })},
            ],
            { cancelable: false }
          )
         }

         
         
        }
        
      })
      .catch(error => {
        this.setState({ isLoading: false });
        console.log(error);
      });
    
  }
  
    
  }

  async getOfferDetail(storyID,sent_primary_id){
    // multipleData = [];
    
    let session_user_id = 2;
      await AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
          username = data[0][1];
          // password = data[1][1];
          session_user_id = data[2][1];
        });
      //   alert(session_user_id);
      //   return false;
      // this.setState({ isLoading: true });
      let formBody = "user_id="+session_user_id+"&story_id="+storyID+"&sent_primary_id="+sent_primary_id+"";
      // setTimeout(() => {
      //   this.setState({ isLoading: false });
      // }, 10000);
      if (username !== null && username !== ''){
      fetch(
            // 'http://192.168.1.23/10k-club/webservices/get_user_profile.php',
            // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
            getOfferDetailById,
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
        
        this.setState({ 
          story_description: res.response.story_description,
          campaign_description: res.response.campaign_description,
        });
        this.setState({ isLoading: false });
        // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
        //this.setState.multipleSelectedData.includes(res.user_details.favourites);
        return res.response.story_description;
      }
      else {
        this.setState({ isLoading: false });
        alert(res.message);
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

    return;
    }
  
  componentWillMount() {
    StatusBar.setHidden(true);
  //alert(isRTL);
  AsyncStorage.multiGet(['user_profile_pic_url']).then((data) => {
    let user_profile_pic_url = data[0][1];
    this.setState({
      user_profile_pic_url : user_profile_pic_url
    })
  });
  
}
componentWillUnmount(){
  // alert("unmount");
  // if(this.notification_flag == true){
    
  // }else{
  //   const {params} = this.props.navigation.state;
  //   params.callHome();
  // }

  
    const {params} = this.props.navigation.state;
    if(params){
      // alert(JSON.stringify(params));
      params.callHome();
    }
}
    _renderItemApprovedUsers(item,index){
        // alert(item.key);
        // alert(JSON.stringify(item.profile_pic_url));
        return (
            <View style={{flex:1}}>
            { 
                index==0 &&
                <View style={{alignItems: 'flex-start',marginLeft:10}}>

                <Image style={{width: 25, height: 25, borderRadius:25/2, position:'absolute', left:0}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>1</Text>
                </View> */}
                
                </View>
            }

            { 
                index==1 &&
                <View style={{alignItems: 'flex-start'}}>
                <Image style={{width: 25, height: 25, borderRadius:25/2, zIndex:9999, marginTop:0, position:'absolute', left:18}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>2</Text>
                </View> */}
                </View>
            }

            { 
                index==2 &&
                <View style={{alignItems: 'flex-start'}}>
                <Image style={{width: 25, height: 25, borderRadius:25/2, zIndex:9999, marginTop:0, position:'absolute', left:36}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>3</Text>
                </View> */}
                </View>
            }
                
            
            </View>
                
                
             
        )
    }

 render() {
   let i = 1;
      const { navigation } = this.props;
      const item = navigation.getParam('item', 'NO-ID');
      if(item.description_type ==1 ){
        // alert('hhhhh');
        // this.getOfferDetail(item.id,item.sent_primary_id);
        // this.setNotificationFlag(true);
        // noti = true;
      }else{
        // if(item.description_type ==2 && i == 1){
        //   this.setNotificationFlag(false);
        //   i++;
        //   // break;
        // }
        this.state.story_description;
        this.state.campaign_description;
      }
      //let user_profile_pic_url = AsyncStorage.get("user_profile_pic_url");
        
        return (


            <View style={styles.container}>
                <ImageBackground source={Images.screen_6} resizeMode="stretch" style={styles.container}>
                <ScrollView>
                <View style={{flex:1,flexDirection:'row'}}>
                <View style={{flex:0.5,...ifIphoneX({
            marginTop: 30,

        },{
            marginTop: 10,

        }),marginLeft:30}}>
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
      {/* <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity> */}
                 
                 <View style={{ flex: 1,flexDirection:'row',marginHorizontal:0}}>
                 <View style={styles.profileImage}>
                    
              
              
                    
                    {item.media_type == 1 &&
                        <Image source={{uri : (item.media_type==2) ? item.story_video_thumb : item.story_image}}
                        style={{width: 102, height: 140}} />
                      }
                    
                    <View >
                                               {item.media_type == 2 &&
                                               <View >
                                                 {/* <Text>{item.story_video}</Text> */}
                                               {/* { Platform.OS=="ios" &&  */}
                                            <Video
          source={{uri:item.story_video}}
          style={{ width: 102, height: 140 }}
          muted={true}
          repeat={true}
          resizeMode={"cover"}
          volume={1.0}
          rate={1.0}
          ignoreSilentSwitch={"obey"}
          controls={true}
          fullscreen={true}
          fullScreenOnLongPress={true}

        />
        
        
        </View>
                                               }
        </View>
              </View>
                    <View style={styles.infoContainer}>
                    <View style={{borderBottomWidth:0.4,borderColor: "#ffffff",marginVertical:10}}>
                    <Text style={styles.userFullName}>{item.story_title}</Text>
                    </View>
                    
                    <Text style={styles.followerCount}>{item.short_description}</Text>
                    <View style={{flex:1,flexDirection:'row',textAlign:'left',marginVertical:16}}>
                <FlatList
                contentContainerStyle={styles.list}
                            // horizontal={true}
                            data={item.approved_users}
                            // SeparatorComponent={() => <View  style={{width: 5}}/>}
                            renderItem={({item,index}) => this._renderItemApprovedUsers(item,index)}
                        />

                <View style={{flexDirection:'row',textAlign:"left",marginRight:0,marginLeft:5}}>
                <Text style={{fontSize:10,justifyContent:'center',color:"#fff",marginTop:5}}>{  (item.approved_users.length !== 0) ? 'friends joined the offer '+item.approved_users[0].total_approved_count : ''}</Text>
                </View>
                
                </View>
                    </View>
                 
                    </View>
                    
                    </View>
                    <View style={styles.secondrow}>
                  <Text style={styles.OfferDetails_tit}>OfferDetails:</Text>
                  <Text style={styles.OfferDetails_dis}>{ (item.description_type==1) ? item.story_description : item.story_description } {'\n'}{'\n'}</Text>
                  </View>

                  {/* //firstrow end */}


                  
                    {/* //secndrow end */}

                    <View style={styles.thirdrow}>
                    <Text style={styles.thirdrow_text1}>Requirements</Text>
                    <Text style={styles.thirdrow_text2}>{item.campaign_schedule_count} Story posts</Text>
                    <Text style={styles.thirdrow_text1}>Story schedule</Text>
                    {item.campaign_schedule.length != 0 && 
                    <View>
                    {item.campaign_schedule.map(schedule => (
                      <View>
                    <Text style={styles.thirdrow_text3}>{schedule.schedule_info} </Text>
                    </View>
                    ))}
                    </View>
                    }
                    {/* <Text style={styles.thirdrow_text3}>Sunday 3.3.19|12:00</Text> */}
                    <View style={{ flex: 1,flexDirection:'row',marginTop: 5,marginHorizontal:14}}>
                    <Text style={styles.thirdrow_text4}>Reward:</Text>
                    <Text style={styles.thirdrow_text5}>{item.reward}</Text>
                    </View>
                  </View>
                 {/* //thiredrow end */}
                  { item.upload_status!=1 && item.story_accept_status!=1 &&
                 <View style={styles.fourthrow}>

                 <Text style={styles.fourthrow_text1}>Confirm offer</Text>
                 <CheckBox
                title='I have read the agreement and agree to the terms and conditions.'
                
                textStyle={styles.followerCount}
                containerStyle={{ marginLeft: 10, marginRight: 10, padding: 0, borderWidth: 0, backgroundColor: 'transparent'}}
                checked={this.state.checked}
                textStyle={styles.infotext}
                size={20}
                checkedColor={'#D6C600'}
                onIconPress={checked => this.setState({ checked: !this.state.checked, modalVisible: false })}
                onPress={checked => this.setState({ checked: !this.state.checked, modalVisible: false })}/>
                
 <View style={styles.logoContainer}>
                           

                           <TouchableOpacity onPress={() => this.acceptStory(item.id,item.sent_primary_id,'',type=1)} 
                            
                            >
                            <View style={{alignItems:'center'}}>
                           <Image style={styles.logo}
                               source={Images.thumbBtn}>
                           </Image>

                           <Text style={styles.text_register}>Approve</Text>
                           </View>
                           </TouchableOpacity>





                       </View>

                       {
						this.state.isLoading &&
						<Spinner />
          }
                  </View>
                  }
                  { item.upload_status ==1 && item.story_accept_status==1 &&
                  <View>
                    <Text style={{textAlign:'center',fontSize:14,color:'#fff',marginTop:30}}>Already Approved!</Text>
                  </View>
                  }
                 {/* //fourthrow end */}

      {/* </View>  */}
      </ScrollView>
<TouchableWithoutFeedback onPress={() => this.showDialog(true)}>
      <DialogInput isDialogVisible={this.state.isDialogVisible}
            title={"Authentication"}
            message={"Please enter password again."}
            hintInput ={"Password"}
            submitInput={ (inputText) => {this.sendInput(inputText,type=2,item.id,item.sent_primary_id)} }
            textInputProps={{autoCorrect:false }} 
            cancelText= {' '}  
            closeDialog={ () => {}}
          
            >
</DialogInput>
</TouchableWithoutFeedback>
                </ImageBackground>
                
            </View>
        );
    }
}

const styles = StyleSheet.create({

    container: {
        flex: 1,
        flexDirection:'column', 
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
            marginTop: 0,

        }),
        justifyContent:'flex-end',
        marginHorizontal:30,
        alignItems:'flex-start'
    },
    profileimg:
    {
        //flex: 0.5,
        //alignItems:'flex-end',
        
        paddingTop: ( Platform.OS === 'ios' ) ? 20 : 0,
        marginLeft:10

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
          marginHorizontal: 10
          // zIndex:1
      },

      firstrow: {
    
        flex: 0.7,
        // justifyContent:'flex-start',
        flexDirection: isRTL ? 'row-reverse' : 'row',
        //backgroundColor: "#ffc857",
        borderBottomWidth:0.1,
        borderColor: "#727171",
        marginTop: 10,
        //marginHorizontal:17
    
      },
    
      profileImage: {
        flex: 0.4,
        //justifyContent: 'center',
          alignItems: isRTL ? 'flex-start' : 'flex-end',
          //margin: 5,
          paddingTop: ( Platform.OS === 'ios' ) ? 20 : 0,
          marginLeft:5
      },
      infoContainer: {
      flex:0.6,
      //justifyContent: 'center',
      alignItems: isRTL ? 'flex-end' : 'flex-start',
      //margin: 20,
      marginHorizontal:22,
      paddingTop: ( Platform.OS === 'ios' ) ? 5 : 0
      },
      userFullName: {
        color: "#fff",
        fontSize: 18,
        paddingBottom: 4,
        fontFamily: 'Montserrat-Regular',
        //lineHeight:0.1
        // marginHorizontal:30,
        

      },
      followerCount: {
        color: "#fff",
        fontFamily: 'Montserrat-Regular',
        fontSize: 13
      },
      list: {
        flex:1,
        alignContent:'space-between',
        flexDirection: "row",
        // flexWrap: "wrap",
        paddingBottom: 35
        
        },
        secondrow: {
    
            flex: 0.6,
            margin: 5,
            // justifyContent:'flex-start',
            // flexDirection: isRTL ? 'row-reverse' : 'row',
            //backgroundColor: "#ffc857",
            borderBottomWidth:0.3,
            borderColor: "#727171",
            // marginTop: 20,
            marginHorizontal:17
        
          },

          OfferDetails_dis: {
            color: "#fff",
            fontSize: 14,
            paddingBottom: 10,
            marginTop:5,
            
            fontFamily: 'Montserrat-Regular',
            marginHorizontal:18,
            //lineHeight:0.1
            // marginHorizontal:30,
            
    
          },
          OfferDetails_tit: {
            marginHorizontal:18,
            color: "#fff",
            fontFamily: 'Montserrat-Regular',
            fontSize: 16,
            marginTop:3,
            marginBottom:10
          },

          thirdrow: {
    
            flex: 0.7,
            // justifyContent:'flex-start',
            // flexDirection: isRTL ? 'row-reverse' : 'row',
            //backgroundColor: "#ffc857",
            borderBottomWidth:0.3,
            borderColor: "#727171",
            marginTop: 10,
            marginHorizontal:17
        
          },

          thirdrow_text1:
          {
            color: "#0B96B4",
            fontSize: 15,
            fontFamily: 'Montserrat-Regular',
            paddingBottom: 4,
            marginHorizontal:18,
          },
          thirdrow_text2:
          {
            
                color: "#fff",
                fontSize: 15,
                paddingBottom: 4,
                fontFamily: 'Montserrat-Regular',
                marginHorizontal:18,
                marginTop: 4,
          },
          thirdrow_text3:
          {
            color: "#D6C600",
            fontSize: 15,
            paddingBottom: 4,
            fontFamily: 'Montserrat-Regular',
            marginHorizontal:18,
            marginTop: 4,
          },
          thirdrow_text4:
          {
            color: "#D6008F",
            fontSize: 15,
            paddingBottom: 4,
            marginHorizontal:4,
            fontFamily: 'Montserrat-Regular',
            marginTop: 8,
          },
          thirdrow_text5: {
            color: "#fff",
            fontSize: 14,
            fontFamily: 'Montserrat-Regular',
            paddingBottom: 4,
            //marginRight:40,
            marginTop: 8,
          },
          fourthrow: {
    
            flex: 1,
            // justifyContent:'flex-start',
            // flexDirection: isRTL ? 'row-reverse' : 'row',
            //backgroundColor: "#ffc857",
            //borderBottomWidth:1,
            //borderColor: "#727171",
            //marginTop: 20,
            //marginHorizontal:7
            marginTop: 10,
            marginHorizontal:17
        
          },
          fourthrow_text1:
          {
            color: "#D6C600",
            fontSize: 15,
            paddingBottom: 4,
            marginHorizontal:18,
            marginTop: 4,
            fontFamily: 'Montserrat-Regular',
            textAlign: 'center',
          },
          logo: {
            width: width/8,
            height: height/13,
        },
        logoContainer: {
            alignItems: 'center',
            justifyContent: 'flex-start',
            flex: 1,
            top: 28,
            marginBottom:90
    
        },
        text_register: {
            textAlign: 'center',
            color: '#fff',
            fontSize: 15,
            fontFamily: 'Montserrat-Regular',
            marginTop: 10
        
        },infotext: {
            //textAlign: 'center',
            color: '#fff',
            fontSize: 12,
            fontFamily: 'Montserrat-Regular',
            marginTop: 10
        
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
});