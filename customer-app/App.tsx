import React, { useState } from 'react';
import { createStackNavigator,TransitionPresets } from '@react-navigation/stack';
import { NavigationContainer, useNavigation } from '@react-navigation/native';
import { createDrawerNavigator, DrawerContentScrollView } from '@react-navigation/drawer';
import { StyleSheet, Text, TouchableOpacity, View, Image } from 'react-native'
import Icon, { Icons } from './src/components/Icons';
import * as colors from './src/assets/css/Colors';
import { screenWidth, bold, normal, regular, logo, img_url } from './src/config/Constants';
import { connect } from 'react-redux';
import Dialog from "react-native-dialog";

/* Screens */
import Splash from './src/views/Splash';
import LocationEnable from './src/views/LocationEnable'; 
import Intro from './src/views/Intro'; 
import Forgot from './src/views/Forgot'; 
import Dashboard from './src/views/Dashboard'; 
import Faq from './src/views/Faq';
import EmergencyContacts from './src/views/EmergencyContacts'; 
import Subscription from './src/views/Subscription'; 
import MyRides from './src/views/MyRides'; 
import Wallet from './src/views/Wallet';
import Profile from './src/views/Profile';
import Notifications from './src/views/Notifications';
import TripDetails from './src/views/TripDetails';
import CheckPhone from './src/views/CheckPhone';
import Password from './src/views/Password';
import OTP from './src/views/OTP';
import CreateName from './src/views/CreateName';
import CreateEmail from './src/views/CreateEmail';
import CreatePassword from './src/views/CreatePassword';
import ResetPassword from './src/views/ResetPassword';
import Bill from './src/views/Bill';
import PaymentMethod from './src/views/PaymentMethod';
import WriteRating from './src/views/WriteRating';
import PrivacyPolicies from './src/views/PrivacyPolicies';
import AboutUs from './src/views/AboutUs';
import Refer from './src/views/Refer';
import Terms from './src/views/Terms';
import ComplaintCategory from './src/views/ComplaintCategory';
import ComplaintSubCategory from './src/views/ComplaintSubCategory';
import Logout from './src/views/Logout';
import FaqDetails from './src/views/FaqDetails'; 
import Promo from './src/views/Promo'; 
import EditFirstName from './src/views/EditFirstName'; 
import EditLastName from './src/views/EditLastName'; 
import EditEmail from './src/views/EditEmail'; 
import Rating from './src/views/Rating';
import NotificationDetails from './src/views/NotificationDetails'; 
import AddEmergencyContact from './src/views/AddEmergencyContact';
import Paypal from './src/views/Paypal';
import CreateComplaint from './src/views/CreateComplaint';
import Chat from './src/views/Chat';
import AdminChat from './src/views/AdminChat';
import ScrollTest from './src/views/ScrollTest';
import AppUpdate from './src/views/AppUpdate'; 
import temp from './src/views/temp'; 

const Stack = createStackNavigator();
const Drawer = createDrawerNavigator();

function CustomDrawerContent(props) {
  const navigation = useNavigation();
  const [dialog_visible, setDialogVisible] = useState(false);

  const showDialog = () => {
    setDialogVisible(true);
  }

  const closeDialog = () => {
    setDialogVisible(false);
  }

  const handleCancel = () => {
    setDialogVisible(false)
  }

  const handleLogout = async () => {
    closeDialog();
    navigation.navigate('Logout');
  }

  return (
    <DrawerContentScrollView {...props}>
      <View style={{ padding:10, alignItems:'flex-start' }}>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Dashboard') }} style={{ padding:20, alignItems:'flex-end', width:'100%' }}>
          <Icon type={Icons.MaterialIcons} name="close" color={colors.icon_inactive_color} style={{ fontSize:25 }} />
        </TouchableOpacity>
        <View style={{ flexDirection:'row', width:'100%', alignItems:'center'}}>
          <View style={{ width:'40%', alignItems:'center'}}>
            <View style={{ width:100, height:100 }} >
                <Image style= {{ height: undefined,width: undefined,flex: 1, borderRadius:25 }} source={{ uri : img_url+global.profile_picture }} />
            </View>
          </View>
          <View style={{ width:'60%'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.theme_fg_two, fontSize:20, fontFamily:normal }}>Hello,</Text>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.theme_fg_two, fontSize:25, fontFamily:bold,letterSpacing:1 }}>{global.first_name}</Text>
          </View>
        </View>
      </View>
      <View style={{ margin:10 }} />
      <View style={{ padding:10 }}>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Dashboard') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="home" color={colors.theme_fg_two} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.theme_fg_two, fontSize:18, fontFamily:regular }}>Dashboard</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('MyRides') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="local-taxi" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>My Rides</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>Ride histories, Invoice, Complaints</Text>
          </View>
        </TouchableOpacity>
        {/*<TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Subscription') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="card-membership" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Subscription</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>Buy subscription for your free rides</Text>
          </View>
        </TouchableOpacity>*/}
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Profile') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="account-circle" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Profile Settings</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>Edit your profile details</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Wallet') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="payments" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Wallet</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Notifications') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="notifications" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Notifications</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('EmergencyContacts') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="contacts" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Emergency Contacts</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>Add SOS contacts</Text>
          </View>
        </TouchableOpacity>
        {/*<TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Refer') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="share" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Refer & Earn</Text>
          </View>
        </TouchableOpacity>*/}
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('Faq') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="help-outline" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>FAQ's</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>How can we help you?</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('PrivacyPolicies') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="article" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Privacy Policies</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('AboutUs') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="info" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>About Us</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { navigation.navigate('AdminChat') }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="chat" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Admin Chat</Text>
            <View style={{ margin:3 }} />
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:12, fontFamily:normal }}>Contact admin for emergency purpose</Text>
          </View>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={() => { showDialog() }} style={{ flexDirection:'row', width:'100%', margin:15}}>
          <View style={{ width:'15%', alignItems:'center', justifyContent:'center'}}>
            <Icon type={Icons.MaterialIcons} name="logout" color={colors.text_grey} style={{ fontSize:25 }} />
          </View>
          <View style={{ width:'85%', alignItems:'flex-start', justifyContent:'center'}}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color:colors.text_grey, fontSize:18, fontFamily:regular }}>Logout</Text>
          </View>
        </TouchableOpacity>
      </View>
      <Dialog.Container visible={dialog_visible}>
          <Dialog.Title>Confirm</Dialog.Title>
          <Dialog.Description>
            Do you want to logout?
          </Dialog.Description>
          <Dialog.Button label="Yes" onPress={handleLogout} />
          <Dialog.Button label="No" onPress={handleCancel} />
      </Dialog.Container>
    </DrawerContentScrollView>
  );
}

function MyDrawer() {
  return (
    <Drawer.Navigator 
      drawerContent={props => <CustomDrawerContent {...props} />} 
      initialRouteName="Dashboard"
      drawerStyle={{ width: 350, backgroundColor:colors.theme_fg_three }}
      screenOptions={{
        drawerStyle: {
          backgroundColor:colors.theme_fg_three,
          width: screenWidth,
        },
      }}
    >
      <Drawer.Screen
        name="Dashboard"
        component={Dashboard}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="MyRides"
        component={MyRides}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Faq"
        component={Faq}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Wallet"
        component={Wallet}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Notifications"
        component={Notifications}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Profile"
        component={Profile}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="PrivacyPolicies"
        component={PrivacyPolicies}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="AboutUs"
        component={AboutUs}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Refer"
        component={Refer}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="EmergencyContacts"
        component={EmergencyContacts}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Subscription"
        component={Subscription}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="AdminChat"
        component={AdminChat}
        options={{headerShown: false}}
      />
      <Drawer.Screen
        name="Logout"
        component={Logout}
        options={{headerShown: false}}
      />
    </Drawer.Navigator>
  );
}

function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Splash" screenOptions={({ route, navigation }) => ({
                        ...TransitionPresets.SlideFromRightIOS,
                    })} options={{headerShown: false}}  >
        <Stack.Screen name="Splash" component={Splash} options={{headerShown: false}} />
        <Stack.Screen name="CheckPhone" component={CheckPhone} options={{headerShown: false}} />
        <Stack.Screen name="Password" component={Password} options={{headerShown: false}} />
        <Stack.Screen name="OTP" component={OTP} options={{headerShown: false}} />
        <Stack.Screen name="CreateName" component={CreateName} options={{headerShown: false}} />
        <Stack.Screen name="CreateEmail" component={CreateEmail} options={{headerShown: false}} />
        <Stack.Screen name="CreatePassword" component={CreatePassword} options={{headerShown: false}} />
        <Stack.Screen name="ResetPassword" component={ResetPassword} options={{headerShown: false}} />
        <Stack.Screen name="LocationEnable" component={LocationEnable} options={{headerShown: false}} />
        <Stack.Screen name="Intro" component={Intro} options={{headerShown: false}}  />
        <Stack.Screen name="Forgot" component={Forgot} options={{headerShown: false}}  />
        <Stack.Screen name="Home" component={MyDrawer} options={{headerShown: false}} />
        <Stack.Screen name="TripDetails" component={TripDetails} options={{headerShown: false}} />
        <Stack.Screen name="Bill" component={Bill} options={{headerShown: false}} />
        <Stack.Screen name="PaymentMethod" component={PaymentMethod} options={{headerShown: false}} />
        <Stack.Screen name="WriteRating" component={WriteRating} options={{headerShown: false}} />
        <Stack.Screen name="Refer" component={Refer} options={{headerShown: false}} />
        <Stack.Screen name="Terms" component={Terms} options={{headerShown: false}} />
        <Stack.Screen name="ComplaintCategory" component={ComplaintCategory} options={{headerShown: false}} />
        <Stack.Screen name="ComplaintSubCategory" component={ComplaintSubCategory} options={{headerShown: false}} />
        <Stack.Screen name="FaqDetails" component={FaqDetails} options={{headerShown: false}} />
        <Stack.Screen name="Promo" component={Promo} options={{headerShown: false}} />
        <Stack.Screen name="EditFirstName" component={EditFirstName} options={{headerShown: false}} />
        <Stack.Screen name="EditLastName" component={EditLastName} options={{headerShown: false}} />
        <Stack.Screen name="EditEmail" component={EditEmail} options={{headerShown: false}} />
        <Stack.Screen name="Rating" component={Rating} options={{headerShown: false}} />
        <Stack.Screen name="NotificationDetails" component={NotificationDetails} options={{headerShown: false}} />
        <Stack.Screen name="AddEmergencyContact" component={AddEmergencyContact} options={{headerShown: false}} />
        <Stack.Screen name="Paypal" component={Paypal} options={{headerShown: false}} /> 
        <Stack.Screen name="CreateComplaint" component={CreateComplaint} options={{headerShown: false}} /> 
        <Stack.Screen name="Chat" component={Chat} options={{headerShown: false}} />
        <Stack.Screen name="ScrollTest" component={ScrollTest} options={{headerShown: false}} />
        <Stack.Screen name="AppUpdate" component={AppUpdate} options={{headerShown: false}} /> 
        <Stack.Screen name="temp" component={temp} options={{headerShown: false}} /> 
      </Stack.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  btn: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 8,
    borderRadius: 16,
  }
})

function mapStateToProps(state) {
  return {
    first_name: state.register.first_name,
    last_name: state.register.last_name,
    email: state.register.email,
  };
}

const mapDispatchToProps = (dispatch) => ({
  updateEmail: (data) => dispatch(updateEmail(data)),
  updateFirstName: (data) => dispatch(updateFirstName(data)),
  updateLastName: (data) => dispatch(updateLastName(data)),
});

export default connect(mapStateToProps, mapDispatchToProps)(App);
