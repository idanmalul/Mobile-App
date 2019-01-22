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
  I18nManager,
  Linking
} from "react-native";
I18nManager.forceRTL(true); 
import { Images, Colors } from "../themes";
import { getEventList, myEventList } from "../constants/apis";
import LinearGradient from "react-native-linear-gradient";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import Video from 'react-native-video';
const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";
const isRTL = I18nManager.isRTL;
export default class MyEventList extends Component {

  static navigationOptions = {
    title: '', // My Event
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon-1.png')}/>;
    },
};
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false,
      defaulterArray:[]
     
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    
    
        
  }
  
  getMyEventList = ()=>{
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
            myEventList,
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
        this.setState({ isLoading: false });
        this.setState({
          defaulterArray:res.response
        });
        
      }
      else {
        this.setState({ isLoading: false });
        // alert('Something went wrong. Please try again.',res.message);
        // alert('No event available!');
        setTimeout(() => {
          Alert.alert(
              'Alert',
              'No event available!',
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

  //------exit app start------//
  componentWillMount() {
    this.getMyEventList();
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
    //this.props.navigation.navigate("Profile"); 
    return true;
  
    
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
  
  <Text style={styles.headerTitle}>
  {/* My Event */}
  הארועים שלי
  </Text>
  
</LinearGradient>
</View>
      <ScrollView>
                    <View style={styles.bodyView}>

                      
                        <View style={{ marginLeft: 15, marginRight: 15, marginTop: 5, marginBottom: 5 }}>
                            
                            {this.state.defaulterArray.length !== 0 &&
                                < View >
                                  
                                    
                                    <View style={{
                                       marginBottom: 10, elevation: 15, flexDirection: 'row',
                                        shadowOpacity: 5, backgroundColor: 'transparent', paddingTop: 5, //paddingBottom: 5
                                    }}>
                                        <FlatList 
                                        ItemSeparatorComponent={this.renderSeparator}
                                        keyExtractor={this._keyExtractor}
                                        data={this.state.defaulterArray}
  
                                        renderItem={this.renderItem.bind(this)}/>
                                    </View>
                                </View>
                            }
                        </View>
                    </View>
                    {
                    this.state.isLoading &&
                    <Spinner />
                }
                </ScrollView>
                
      
      </ImageBackground>
      
    );
  }

  renderItem(data) {
    let { item, index } = data;
    return (
      <View style={styles.itemBlock}>
        {/* <Image source={{uri: item.picture}} style={styles.itemImage}/> */}
        <View style={styles.itemMeta}>
          <Text style={styles.itemName}>{item.story_title}</Text>
          {/* <Text style={styles.itemLastMessage}>{item.last_message}</Text>
          <Text style={styles.itemdate}>{item.time}</Text> */}
        </View>

        <View style={styles.inputMain}>
                    
                      <View style={{
                        flex: 0.5,
                        height: 45,
                       // backgroundColor: "transparent",
                        marginTop: 5,
                         
                        // borderBottomColor: 'gray',
                     //   borderBottomWidth: 1,
                      }}>
                      <Text style={styles.itemLastMessage} onPress={ ()=> Linking.openURL(item.ticket_link) } >כרטיסים לארוע</Text> 
                                </View>
                      <View style={{
                        flex: 0.5,
                        height: 45,
                       // backgroundColor: "transparent",
                        marginTop: 5,
                        right:0,
                        // borderBottomColor: 'gray',
                       marginLeft:15,
                       marginBottom: 0
                      }}>
                        <Text style={styles.itemdate}>{item.created_at}</Text>
                      </View>
                    </View>
      </View>
    ) 
  }

  renderSeparator = () => {
    return (
      <View
        style={{
          height: 1,
          width: "100%",
          backgroundColor: "#CED0CE",
          
        }}
      />
    );
  };
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    width: '100%',
		height:'100%'
  },
  itemBlock: {
    marginTop:10, 
    // flexDirection: isRTL ? 'row-reverse' : 'row',
    paddingBottom: 5,
    // width:'100%'
  },
  // itemImage: {
  //   width: 50,
  //   height: 50,
  //   borderRadius: 25,
  // },
  itemMeta: {
    marginLeft: 10,
    // justifyContent: 'center',
  },
  itemName: {
    fontSize: 15,
    textAlign: 'left',
    color: "#ffffff",
    
    // textAlign: 'right'
  },
  itemdate: {
    fontSize: 15,
    color: "#a7a7a7", 
    textAlign: 'left'
  },
  itemLastMessage: {
    fontSize: 15,
    color: '#32CD32',
    textDecorationLine: 'underline',
    //  textAlign: 'left'
    textAlign: 'right' 
  },
  inputMain: {
    marginTop: 15,
    flexDirection: isRTL ? 'row-reverse' : 'row', 
    alignItems: "center",
    //color:"#fff"
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
    marginLeft: 10,
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.colorWhite,
    fontWeight: "bold",
    marginTop: 10
  },
  icon: {
    width: 28,
    height: 28,
  },
});
