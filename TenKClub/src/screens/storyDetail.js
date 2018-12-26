import React, { Component } from "react";

import _ from "lodash";
import {
  Platform,
  StyleSheet,
  Text,
  Dimensions,
  AsyncStorage,
  View,
  Image,
  TouchableOpacity,
  ScrollView,
  ImageBackground,
  BackHandler,
} from "react-native";
import { Images, Colors } from "../themes";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import Video from 'react-native-video';

const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import { uploadEvent } from "../constants/apis";

export default class Profile extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false, 
  
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
     
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
    this.props.navigation.navigate("storyList");
        return true;
/*    AsyncStorage.multiGet(['username', 'password']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      
      if (username !== null && username !== ''){
        // this.props.navigation.goBack(null);
        // this.props.navigation.goBack("storyList");
        this.props.navigation.navigate("storyList");
        return true;
          
      }else{ 
        this.props.navigation.navigate("Login");
        return true;
      } 
});  */
    
  }

  acceptStory(storyID) {

    AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
      let username = data[0][1];
      let password = data[1][1];
      let session_user_id = data[2][1];
      let formBody = "user_id="+session_user_id+"&story_id="+storyID+"";
      if (username !== null && username !== ''){
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
    
    .then((response)=> response.json())
    
    .then((res) => {
      
      if(res.status === 'true'){
        alert("Story uploaded successfully!");
      }
      else {
        alert('Something went wrong. Please try again.',res.message);
      }
    
    
    })
    
     .done();
  }
});
    
}
  //------exit app end------//

  render() {
    const { navigation } = this.props;
    const item = navigation.getParam('item', 'NO-ID');
    return (
      
      <ImageBackground source={Images.screen_5}  resizeMode="stretch" style={styles.container}>
      <View>
                        <View>
                            {/* <Text style={styles.headerTitle}>{item.type }</Text> */}
                            </View>
        
      </View>
      <ScrollView>
                    
      <View style={{
      justifyContent: 'center',
      textAlign:'center' 
    }}>
            
                <View style={{marginTop:50,marginLeft:20,marginRight:20}}>
                
                    <View style={styles.imageContainer}>
                    
                    {item.media_type == 1 &&
                        <Image style={styles.image} source={{uri:item.story_image}}/>
                      }
                    </View>
                    <View >
                                               {item.media_type == 2 &&
                                            <Video
          source={{uri:item.story_image}}
          style={{ width: 320, height: 200 }}
          muted={true}
          repeat={true}
          resizeMode={"cover"}
          volume={1.0}
          rate={1.0}
          ignoreSilentSwitch={"obey"}

        />
                                               }
        </View>
                    <View style={{alignItems:"center"}}>
                          <Text style={{color:"#fff",
                        fontWeight:"bold",
                        // fontFamily:"Arimo",
                        fontSize:18,
                        marginTop:10
                        }}>{item.story_title}</Text>
                        <Text style={{color:"#a7a7a7",
                            fontWeight:"bold",
                            fontFamily:"Arimo",
                            fontSize:16,
                            marginTop:5
                            }}>{item.created_at}</Text>
                          
                        </View>
                        <View style={styles.acceptButtonContainer}>
                            <TouchableOpacity onPress={() => this.acceptStory(item.id)} style={styles.acceptButton}>
                                    <Text style={styles.buttonText}>Approval of advertising an event</Text>
                                </TouchableOpacity>
                            </View>

                            <Text style={styles.addText}>{item.story_description}</Text>

                          <View style={{alignItems:'center',marginTop:10}}>
                            <TouchableOpacity style={{backgroundColor: '#000',
                            borderColor:'#fff',
                            borderWidth:1,
    paddingVertical: 8,
    paddingHorizontal:30,
    borderRadius:100,
    }}>
                                    <Text style={styles.buttonText}>Read More</Text>
                                </TouchableOpacity>
                            </View>
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
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 16 : 18) : 18,
    color: Colors.colorWhite,
    fontFamily: "Arimo",
    fontWeight: "bold",
    marginTop: 30,
    textAlign: 'center',
  },
  addText: {
    color: "#a7a7a7",
    fontSize: 15
  },
  
  
  bodyView: {
    
    justifyContent: 'center',
    alignItems: "center",
    
    marginLeft:20,
    marginRight:20
  },
  title: {
    textAlign: "center",
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.orangeColor
  },
  
  imageContainer:{
    alignItems:'center'    
  },
  image:{
    flexGrow:1,
    width:320, 
    height:200  
  },
  acceptButtonContainer:{
    position: 'relative',
    padding: 15,    
  },
  acceptButton: {
    backgroundColor: '#1db954',
    paddingVertical: 15,
    borderRadius:100
  },
  buttonText: {
    textAlign: 'center',
    color :'#ffffff',
    fontWeight: 'bold',
    fontSize: 18
},
backgroundVideo: {
  position: 'absolute',
  top: 0,
  left: 0,
  bottom: 0,
  right: 0,
},
});
