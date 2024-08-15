import React, { useState } from "react";
import {
  TouchableOpacity,
  Text,
  StyleSheet,
  View,
  SafeAreaView,
  ScrollView,
  Image,
  TextInput,
  StatusBar
} from "react-native";
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth, normal, bold, regular, f_m, f_l, f_s } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';
import { Rating, AirbnbRating } from 'react-native-ratings';

const WriteRating = (props) => {
  const navigation = useNavigation();
  const [comment, setComment] = useState('');

  const go_back = () => {
    navigation.goBack();
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar
        backgroundColor={colors.theme_bg}
      />
      <View style={[styles.header]}>
        <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ marginLeft: '85%', width: '15%', alignItems: 'center', justifyContent: 'center' }}>
          <Icon type={Icons.MaterialIcons} name="close" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
        </TouchableOpacity>
      </View>
      <View style={{ position: 'absolute', bottom: 0, width: '100%', height: 100, alignItems: 'center', justifyContent: 'center' }}>
        <TouchableOpacity activeOpacity={1} style={{ width: '90%', backgroundColor: colors.btn_color, borderRadius: 10, height: 50, flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
          <Text style={{ color: colors.theme_fg_two, fontSize: f_m, color: colors.theme_fg_three, fontFamily: bold }}>Submit</Text>
        </TouchableOpacity>
      </View>
      <ScrollView>
        <View style={{ padding: 20 }}>
          <View style={{ alignItems: 'center', justifyContent: 'center' }}>
            <View style={{ height: 50, width: 50, borderRadius: 25 }} >
              <Image style={{ height: undefined, width: undefined, flex: 1 }} source={require(".././assets/img/temp/avatar.webp")} />
            </View>
            <View style={{ margin: 10 }} />
            <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: f_m, fontFamily: bold }}>Sarath Kannan</Text>
          </View>
        </View>
        <View style={{ margin: 10 }} />
        <View style={{ padding: 20 }}>
          <View style={{ alignItems: 'center', justifyContent: 'center', }}>
            <Text numberOfLines={1} style={{ color: colors.theme_fg_two, fontSize: f_l, fontFamily: bold }}>How was your trip?</Text>
            <View style={{ margin: 5 }} />
            <Text style={{ color: colors.grey, lineHeight: 25, fontSize: f_s, fontFamily: normal, textAlign: 'center' }}>Your feedback will help improve driving experience</Text>
          </View>
          <View style={{ margin: 20 }} />
          <AirbnbRating
            count={5}
            reviews={["Terrible", "Bad", "OK", "Very Good", "Wow"]}
            defaultRating={5}
            size={30}
            style={{ padding: 10 }}
          />
          <View style={{ margin: 25 }} />
          <TextInput
            secureTextEntry={false}
            multiline={true}
            numberOfLines={5}
            placeholder="Enter your comment"
            textAlignVertical="top"
            placeholderTextColor={colors.grey}
            style={styles.textinput}
            onChangeText={TextInputValue =>
              setComment(TextInputValue)}
          />
        </View>
      </ScrollView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: {
    ...StyleSheet.absoluteFillObject,
    height: screenHeight,
    width: screenWidth,
    backgroundColor: colors.lite_bg,
  },
  header: {
    height: 60,
    backgroundColor: colors.lite_bg,
    flexDirection: 'row',
    alignItems: 'center'
  },
  textinput: {
    fontSize: f_m,
    color: colors.grey,
    fontFamily: regular,
    backgroundColor: colors.theme_bg_three,
    width: '100%',
    padding: 10,
    borderRadius: 10
  },
});

export default WriteRating;