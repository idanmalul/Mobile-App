import React, { Component } from "react";
// import { ReadMore, RegularText } from 'react-native-read-more-text';
import ViewMoreText from 'react-native-view-more-text';
  

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
  Alert,
  I18nManager
} from "react-native";
I18nManager.forceRTL(true); 
import { Images, Colors } from "../themes";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import Video from 'react-native-video';
const isRTL = I18nManager.isRTL;
const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
import { uploadEvent } from "../constants/apis";


export default class storyDetails extends Component {

  static navigationOptions = {
    title: '', // story details
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon-1.png')}/>;
    },
};
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false, 
      isUploaded: false
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
     
  }
  renderViewMore(onPress){
    return(
    //   <Text onPress={onPress}>View more</Text>
    //   <Text onPress={onPress} style={styles.buttonText}>
                                    
    //                                 קרא עוד
    //                                 </Text>
                                    <View style={{alignItems:'center',marginTop:10}}>

                                    <TouchableOpacity onPress={onPress} style={{backgroundColor: '#000',
                                    borderColor:'#fff',
                                    borderWidth:1,
            paddingVertical: 8,
            paddingHorizontal:30,
            borderRadius:100,
            }}>
                                          
                                            <Text style={styles.buttonText}>
                                            
                                            קרא עוד
                                            </Text>
                                        </TouchableOpacity>
                                    </View>
    )
  }
  renderViewLess(onPress){
    return(
    //   <Text onPress={onPress}>View less</Text>
    //   <Text onPress={onPress} style={styles.buttonText}>
                                    
    //                                 קרא עוד
    //                                 </Text>
    <View style={{alignItems:'center',marginTop:10}}>

                                    <TouchableOpacity onPress={onPress} style={{backgroundColor: '#000',
                                    borderColor:'#fff',
                                    borderWidth:1,
            paddingVertical: 8,
            paddingHorizontal:30,
            borderRadius:100,
            }}>
                                          
                                            <Text style={styles.buttonText}>
                                            
                                            תקרא פחות
                                            </Text>
                                        </TouchableOpacity>
                                    </View>
    )
  }

 async acceptStory(storyID) {
      
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
    let formBody = "user_id="+session_user_id+"&story_id="+storyID+"";
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
      if(res.status === 'true'){
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
          {text: 'OK', onPress: () => this.setState({ isLoading: false })},
        ],
        { cancelable: false }
      )
      }
      else {
        console.log("Data false");
        // this.setState({ isLoading: false });
        // this.setState({ isLoading: false });
       // alert('Something went wrong. Please try again.',res.message);
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
      
    })
    .catch(error => {
      this.setState({ isLoading: false });
      console.log(error);
    });
  
}

  
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
    this.props.navigation.goBack();
    // this.props.navigation.navigate("storyList");
        return true;
    
  }

  // closeActivityIndicator = () => setTimeout(() => this.setState({
  //   isLoading: false }), 10000)
  //   componentDidMount = () => this.closeActivityIndicator()
  //------exit app end------//
  _renderTruncatedFooter = (handlePress) => {
    return (
      <RegularText style={{color: Colors.tintColor, marginTop: 5}} onPress={handlePress}>
        Read more
      </RegularText>
    );
  }

  _renderRevealedFooter = (handlePress) => {
    return (
      <RegularText style={{color: Colors.tintColor, marginTop: 5}} onPress={handlePress}>
      Show less
        
      </RegularText>
    );
  }

  _handleTextReady = () => {
    // ...
  }
  render() {
    //let { text } = this.props; 
    const { navigation } = this.props;
    const item = navigation.getParam('item', 'NO-ID');
    //let { text } = item.story_description;
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
                            // fontFamily:"Arimo",
                            fontSize:16, 
                            marginTop:5
                            }}>{item.created_at}</Text>
                          
                        </View>
                        <View style={styles.acceptButtonContainer}>
                          {
                              !this.state.isUploaded &&
                              
                            
                            <TouchableOpacity onPress={() => this.acceptStory(item.id)} style={styles.acceptButton}
                            
                            >
                                    <Text style={styles.buttonText}>
                                    {/* Approval of advertising an event */}
                                    אישור שיווק ופרסום ארוע
                                    </Text>
                                </TouchableOpacity>
                                }
                            </View>

                            {/* <Text style={styles.addText}>{item.story_description}</Text> */}
                                <View>
                                

<ViewMoreText
          numberOfLines={3}
          renderViewMore={this.renderViewMore}
          renderViewLess={this.renderViewLess}
          textStyle={{textAlign: 'left'}}
        >
          <Text style={styles.addText}>{item.story_description}</Text>
        </ViewMoreText>
                                </View>
                          {/* <View style={{alignItems:'center',marginTop:10}}>

                            <TouchableOpacity style={{backgroundColor: '#000',
                            borderColor:'#fff',
                            borderWidth:1,
    paddingVertical: 8,
    paddingHorizontal:30,
    borderRadius:100,
    }}>
                                  
                                    <Text style={styles.buttonText}>
                                    
                                    קרא עוד
                                    </Text>
                                </TouchableOpacity>
                            </View> */}
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
    // fontFamily: "Arimo",
    fontWeight: "bold",
    marginTop: 30,
    textAlign: 'center',
  },
  addText: {
    // flexDirection: isRTL ? 'row-reverse' : 'row',
    color: "#a7a7a7",
    fontSize: 15,
    textAlign:'left'
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
