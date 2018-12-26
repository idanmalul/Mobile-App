import React, { Component } from "react";

import _ from "lodash";
import {
  Platform,
  StyleSheet,
  FlatList,
  Text,
  Dimensions,
  AsyncStorage,
  View,
  Image,
  TouchableOpacity,
  ScrollView,
  ToastAndroid,
  AlertIOS,
  Alert,
  ImageBackground,
  BackHandler,
} from "react-native";
import { Images, Colors } from "../themes";
import { getEventList } from "../constants/apis";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import Video from 'react-native-video';
const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";

export default class Profile extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false,
      defaulterArray:[]
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    
    this.getStoryList();
        
  }
  
  getStoryList = ()=>{

    AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      let session_user_id = data[2][1];
      let formBody = "user_id="+session_user_id;
      if (username !== null && username !== ''){
          fetch(
            // 'http://192.168.1.23/10k-club/webservices/get_story_list.php',
            // 'http://10k.tempurl.co.il/webservices/get_story_list.php',
            getEventList,
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
      if(res.status === 'true'){
        this.setState({
          defaulterArray:res.response
        });
        
      }
      else {
        // alert('Something went wrong. Please try again.',res.message);
        alert('No event available!');
      }
    
    
    })
    
     .done();
  }
});
    
    }

  //------exit app start------//
  componentWillMount() {
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
    this.props.navigation.navigate("Profile"); 
    return true;
  /*  AsyncStorage.multiGet(['username', 'password']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      if (username !== null && username !== ''){
        // this.props.navigation.goBack(null);
        // this.props.navigation.goBack(null);
        this.props.navigation.navigate("profile"); 
        return true;
          
      }else{
        this.props.navigation.navigate("Login");
        return true;
      }
      
});  */
    
  }

  //------exit app end------//

  render() {

    
    return (
      
      <ImageBackground source={Images.screen_4}  resizeMode="stretch" style={styles.container}>
      <View>

        {/* <LinearGradient
          start={{ x: 0.5, y: 0.25 }}
          end={{ x: 0.5, y: 1.0 }}
          colors={[Colors.mainColor, Colors.colorStatusBar]}
          style={styles.headerView}
        >
          
          <Text style={styles.headerTitle}>Story</Text>
          
        </LinearGradient> */}
        <View style={{marginTop:0}}>
                            <Text style={styles.headerTitle}>The Upcoming Events</Text>
                            </View>
      </View>
      <ScrollView>
                    <View style={styles.bodyView}>

                      
                        <View style={{ marginLeft: 15, marginRight: 15, marginTop: 15, marginBottom: 5 }}>
                            
                            {this.state.defaulterArray.length !== 0 &&
                                < View >
                                  
                                    
                                    <View style={{
                                       marginBottom: 10, elevation: 15, flexDirection: 'row',
                                        shadowOpacity: 5, backgroundColor: 'transparent', paddingTop: 5, //paddingBottom: 5
                                    }}>
                                        <FlatList
                                            data={this.state.defaulterArray}
                                            scrollEnabled={true}
                                            renderItem={({ item, index }) =>

                                            <TouchableOpacity activeOpacity={0.99}
                                        onPress={() =>
                                            this.props.navigation.navigate('storyDetail', {
                                                item: item,
                                            })}
                                        style={{
                                            marginTop: 10, justifyContent: 'center', alignItems: 'center',
                                            backgroundColor: 'transparent', paddingBottom: 25, paddingTop: 5, elevation: 7,
                                            shadowOpacity: 7, paddingLeft:15, paddingRight:15 ,
                                        }}>
                                                <View style={{
                                                  alignItems: 'center', 
                                                  justifyContent: 'space-around',
                                                }}>

                                               
                                            
                                            <View style={styles.imageContainer}>
                                            {item.media_type == 1 &&
                                            <Image style={styles.image} source={{uri:item.story_image}}/>
                                          } 
                                            </View>
                                            
                                               <View>
                                               {item.media_type == 2 &&
                                            <Video
          source={{uri:item.story_image}}
          style={{ width: 455, height: 200 }}
          muted={true}
          repeat={true}
          resizeMode={"cover"}
          volume={1.0}
          rate={1.0}
          ignoreSilentSwitch={"obey"}

        />
                                               }
        </View>
                                               
                                                  <Text style={{color:"#fff",
                                                fontWeight:"bold",
                                                marginTop:10
                                                }}>{item.story_title}</Text>
                                                <Text style={{color:"#b2b2b2",
                                                fontWeight:"bold",
                                                marginTop:5
                                                }}>{item.created_at}</Text>
                                                </View>
                                                </TouchableOpacity>
                                            } />
                                    </View>
                                </View>
                            }
                        </View>
                    </View>
                </ScrollView>
                {
                    this.state.isLoading &&
                    <Spinner />
                }
      
      </ImageBackground>
      
    );
  }

  
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
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
  headerTitle: {
    // marginLeft: 10,
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 16 : 18) : 18,
    color: Colors.colorWhite,
    fontWeight: "bold",
    fontFamily: "Arimo",
    marginTop: 30,
    // alignItems: "center",
    textAlign: 'center',
    padding:10
    // flex:""
  },
  bodyView: {
    backgroundColor: "transparent"
  },
  title: {
    textAlign: "center",
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.orangeColor
  },
  imageContainer:{
    
  },
  image:{
    width:455, 
    height:200
  },
  backgroundVideo: {
    position: 'absolute',
    top: 0,
    left: 0,
    bottom: 0,
    right: 0,
  },
});
