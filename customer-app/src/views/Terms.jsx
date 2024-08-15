import React from "react";
import {
  TouchableOpacity,
  Text,
  StyleSheet,
  View,
  SafeAreaView,
  ScrollView,
  StatusBar
} from "react-native";
import { useNavigation } from "@react-navigation/native";
import * as colors from '../assets/css/Colors';
import { screenHeight, screenWidth, bold, regular, f_25, f_l, f_s } from '../config/Constants';
import Icon, { Icons } from '../components/Icons';

const Terms = (props) => {
  const navigation = useNavigation();

  const go_back = () => {
    navigation.goBack();
  }

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar
        backgroundColor={colors.theme_bg}
      />
      <View style={[styles.header]}>
        <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '85%', alignItems: 'flex-start', justifyContent: 'center' }}>
          <Text numberOfLines={1} ellipsizeMode='tail' style={{ paddingLeft: 20, color: colors.theme_fg_two, fontSize: f_25, fontFamily: bold }}>Terms & Conditions</Text>
        </TouchableOpacity>
        <TouchableOpacity activeOpacity={1} onPress={go_back.bind(this)} style={{ width: '15%', alignItems: 'center', justifyContent: 'center' }}>
          <Icon type={Icons.MaterialIcons} name="close" color={colors.theme_fg_two} style={{ fontSize: 30 }} />
        </TouchableOpacity>
      </View>
      <ScrollView>

        <View style={{ backgroundColor: colors.theme_bg_three, padding: 10, margin: 10, borderRadius: 10 }}>
          <View style={{ margin: 10 }}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_l, fontFamily: bold }}>What is Lorem Ipsum?</Text>
            <View style={{ margin: 5 }} />
            <Text style={{ color: colors.grey, fontSize: f_s, fontFamily: regular }}>
              Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </Text>
          </View>
          <View style={{ margin: 10 }}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_l, fontFamily: bold }}>What is Lorem Ipsum?</Text>
            <View style={{ margin: 5 }} />
            <Text style={{ color: colors.grey, fontSize: f_s, fontFamily: regular }}>
              Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </Text>
          </View>
          <View style={{ margin: 10 }}>
            <Text numberOfLines={1} ellipsizeMode='tail' style={{ color: colors.theme_fg_two, fontSize: f_l, fontFamily: bold }}>What is Lorem Ipsum?</Text>
            <View style={{ margin: 5 }} />
            <Text style={{ color: colors.grey, fontSize: f_s, fontFamily: regular }}>
              Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </Text>
          </View>
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
    backgroundColor: colors.theme
  },
  header: {
    height: 60,
    backgroundColor: colors.lite_bg,
    flexDirection: 'row',
    alignItems: 'center'
  },
});

export default Terms;