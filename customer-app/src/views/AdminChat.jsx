import React from 'react';
import { StyleSheet, View, Text, SafeAreaView, TouchableOpacity, StatusBar } from 'react-native';
import Icon, { Icons } from '../components/Icons';
import * as colors from '../assets/css/Colors';
import { bold, base_url, width_100, f_xl } from '../config/Constants';
import { useNavigation, CommonActions } from '@react-navigation/native';
import { WebView } from 'react-native-webview';
const AdminChat = () => {

  const navigation = useNavigation();

  const navigate_home = () =>{
    navigation.dispatch(
        CommonActions.reset({
            index: 0,
            routes: [{ name: "Home" }],
        })
    );
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar backgroundColor={colors.theme_bg}/>
       <View style={[styles.header]}>
            <TouchableOpacity activeOpacity={1} onPress={navigate_home.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
                <Icon type={Icons.MaterialIcons} name="arrow-back" color={colors.theme_fg_three} style={{ fontSize: 30 }} />
            </TouchableOpacity>
            <View activeOpacity={1} style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
                <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_three, fontSize: f_xl, fontFamily: bold }}>Chat (Admin)</Text>
            </View>
        </View>
        <WebView 
          source={{ uri: base_url+'customer_chat/'+global.id }} 
          style={{ width: width_100 }}
          javaScriptEnabled={true}
          domStorageEnabled={true}
          startInLoadingState={true}
        />
    </SafeAreaView> 
  )
}

const styles = StyleSheet.create({
    container: {
        flex:1,
    },
    header: {
      height: 60,
      backgroundColor: colors.theme_bg,
      flexDirection: 'row',
      alignItems: 'center'
  },
});

export default AdminChat;
