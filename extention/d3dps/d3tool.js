window.D3Tool={
	'data' : {
		'settings' : {
			'url' : {
				'heros_url' : function(){
					return "http://" + host_type + ".battle.net/api/d3/profile/" + hero_code + "/index";
				},
				'hero_url' : function(hero_id){
					return "http://" + host_type + ".battle.net/api/d3/profile/" + hero_code + "/hero/" + hero_id;
				},
				'item_url' : function(item_code){
					return "http://" + host_type + ".battle.net/api/d3/data/" + item_code;
				},
				'skill_url' : function(tip_url){
					return "http://" + host_type + ".battle.net/d3/zh/tooltip/" + tip_url + "?format=jsonp";
				},
				'skill_icon_url' : function(icon_name){
					return "http://" + host_type + ".media.blizzard.com/d3/icons/skills/42/" + icon_name + ".png"
				}
			},
			'item_chinese' : {'head':'頭部','torso':'胸甲','legs':'腿甲','waist':'腰帶','feet':'鞋子','shoulders':'護肩','hands':'手套'
				,'bracers':'護腕','neck':'項鍊','rightFinger':'右戒指','leftFinger':'左戒指','mainHand':'主手','offHand':'副手','匯總':'匯總','基礎屬性':'基礎屬性'
				,'套裝屬性':'套裝屬性'
			},
			'item_priority' : {'head':10,'torso':20,'legs':30,'waist':40,'feet':50,'shoulders':60,'hands':70
				,'bracers':80,'neck':90,'rightFinger':100,'leftFinger':110,'mainHand':120,'offHand':130,'匯總':999
			},//裝備部位排序
			'item_name' : ['head','torso','legs','waist','feet','shoulders','hands'
				,'bracers','neck','rightFinger','leftFinger','mainHand','offHand'
			],//裝備名稱
			'item_percent' : {'Crit_Percent_Bonus_Capped':1,'Crit_Damage_Percent':1,'Steal_Health_Percent':1,'Attacks_Per_Second_Percent':1,
				'Magic_Find':1,'Gold_Find':1,'Hitpoints_Max_Percent_Bonus_Item':1
			},//百分比屬性
			'damage_item' : {'rightFinger':1,'leftFinger':1,'neck':1,
				'mainHand':1,'offHand':1
			},//傷害裝備
			'item_normal_attr' : ['Strength_Item','Dexterity_Item','Intelligence_Item','Vitality_Item','Hitpoints_Max_Percent_Bonus_Item',
				'Hitpoints_On_Hit','Steal_Health_Percent','Crit_Percent_Bonus_Capped','Crit_Damage_Percent',
				'Attacks_Per_Second_Percent','Magic_Find','Gold_Find','Hitpoints_Regen_Per_Second','Armor_Item',
				'Resistance_All','Resistance#Physical','Resistance#Cold','Resistance#Fire','Resistance#Lightning','Resistance#Poision',
				'Resistance#Arcane'
			],//普通屬性展示
			'item_damage_attr' : ['Damage_Weapon_Min','Damage_Weapon_Max','Damage_Bonus_Min','Strength_Item','Dexterity_Item','Intelligence_Item',
				'Vitality_Item','Crit_Percent_Bonus_Capped','Crit_Damage_Percent','Attacks_Per_Second_Percent'
			],//傷害屬性展示
			'set_item2normal' : {'Strength':'Strength_Item','Dexterity':'Dexterity_Item','Intelligence':'Intelligence_Item','Vitality':'Vitality_Item',
				'Resistance':'Resistance_All','Critical Hit Chance Increased by':'Crit_Percent_Bonus_Capped'
			}, //套裝屬性對應普通屬性
			'gem_color' : { 'Strength_Item' : 'red' , 'Dexterity_Item' : 'green' ,'Intelligence_Item' : '#B19611' ,'Vitality_Item' : 'purple' 
				,'Magic_Find' : '#B19611','Gold_Find' : 'green','Crit_Damage_Percent' : 'green'
			},//寶石顏色
			'skill_unlock_level' : {'slot1' : 2 , 'slot2' : 4 , 'slot3' : 9 , 'slot4' : 14 , 'slot5' : 19,'passive_slot0' : 10,'passive_slot1' : 20,'passive_slot2' : 30
			},//技能解鎖等級
			'weapons' : {
				'swords1h':{'speed':1.4,'type':1,'ch':'單手劍'},//單手劍
				'swords2h':{'speed':1.1,'type':2,'ch':'雙手劍'},//雙手劍
				'bow':{'speed':1.4,'type':1,'ch':'雙手弓'},//雙手弓
				'crossbow':{'speed':1.1,'type':1,'ch':'雙手弩'},//雙手弩
				'hand crossbow':{'speed':1.6,'type':1,'ch':'單手弩'},//單手弩
				'mace1h':{'speed':1.2,'type':1,'ch':'單手釘錘'},//單手釘錘
				'mace2h':{'speed':0.9,'type':2,'ch':'雙手釘錘'},//雙手釘錘
				'axes1h':{'speed':1.3,'type':1,'ch':'單手斧頭'},//單手斧頭
				'axes2h':{'speed':1.0,'type':2,'ch':'雙手斧頭'},//雙手斧頭
				'spears':{'speed':1.2,'type':1,'ch':'單手矛'},//單手矛
				'polearms':{'speed':0.95,'type':2,'ch':'雙手長柄'},//雙手長柄
				'daggers':{'speed':1.5,'type':1,'ch':'匕首'},//匕首
				'CeremonialDagger':{'speed':1.4,'type':1,'ch':'祭祀匕首'},//祭祀匕首
				'Mighty_1H':{'speed':1.3,'type':1,'ch':'單手重型武器'},//單手重型武器
				'Mighty_2H':{'speed':1.0,'type':2,'ch':'雙手重型武器'},//雙手重型武器
				'FistWeapon_1H':{'speed':1.4,'type':1,'ch':'拳套武器'}//拳套武器
			},
			'hero_primary_source' : {'barbarian' : {'class':'fury','ch':'怒氣','main_primary':'Strength_Item',
					'passive' : {
						'ruthless':{
							'Crit_Percent_Bonus_Capped_Skill' : 5,
							'Crit_Damage_Percent_Skill' : 50
						},
						'weapons-master' : function(weapon){
							var result = {};
							weapon = weapon.toLowerCase();
							if(weapon.indexOf("mace") == 0 || weapon.indexOf("axe")== 0 ){
								result["Crit_Percent_Bonus_Capped_Skill"] = 10;
							}else if(weapon.indexOf("sword") == 0 || weapon.indexOf("dagger") == 0 ){
								result["Passive_Skill_Percent_Bonus"] = 15;
							}else if(weapon.indexOf("polearm") == 0 || weapon.indexOf("spear") == 0 ){
								result["Attacks_Per_Second_Percent_Skill"] = 10;	
							}
							return result;
						}
					}
				},
				'wizard' :  {'class':'arcanum','ch':'秘能','main_primary':'Intelligence_Item',
					'passive' : {
						'glass-cannon' : {
							"Passive_Skill_Percent_Bonus" : 15
						}
					}
				},
				'monk' : {'class':'spirit','ch':'精氣','main_primary':'Dexterity_Item',
					'passive' : {}
				},
				'witch-doctor' : {'class':'mana','ch':'法力','main_primary':'Intelligence_Item',
					'passive' : {}
				},
				'demon-hunter' : {'class':'hatred-discipline','ch':'憎恨-戒律','main_primary':'Dexterity_Item',
					'passive' : {
						'steady-aim' : {
							"Passive_Skill_Percent_Bonus" : 20
						},
						'archery' : function(weapon){
							var result = {};
							weapon = weapon.toLowerCase();
							if(weapon.indexOf("crossbow") == 0){
								result["Crit_Damage_Percent_Skill"] = 50;
							}else if(weapon.indexOf("bow") == 0){
								result["Passive_Skill_Percent_Bonus"] = 15;
							}else if(weapon.indexOf("hand crossbow") == 0){
								result["Crit_Percent_Bonus_Capped_Skill"] = 10;	
							}
							return result;
						}
					}
				},
				'follower' : {
					'passive' : {
						'anatomy' : {
							"Crit_Percent_Bonus_Capped_Skill" : 3
						},
						'focused-mind' : {
							"Attacks_Per_Second_Percent" : 3
						}
					}
				}
			}//英雄屬性
		},
		'cur_hero' : {},
		'sets' : {}
	},
	'get_data' : function(url,callback,callback_data){
		$.ajax({
	    type:"GET",
	    url:url,
	    dataType: 'jsonp',
	    success:function (data) {
	    	callback(data,callback_data);
	    }
		});
	},
	'get_heros' : function(callback){
		var url = D3Tool.data.settings.url.heros_url(hero_code);
		D3Tool.get_data(url,callback);
	},
	'handle_heros' : function(data){
		html = '<ul class="hero-tabs">';
		if(data.heroes == undefined) data.heroes = [];
		for(var i = 0 ;i < data.heroes.length;i++)
		{
			var class_name = data.heroes[i]["class"];
			if(data.heroes[i]["gender"] == 0)
				class_name += "-male";
			else if(data.heroes[i]["gender"] == 1)
				class_name += "-female";
			html+='<li>\
					<a class="hero-tab ' + class_name + '" href="javascript:#" onclick="D3Tool.get_hero_detail(' + data.heroes[i]["id"] + ',D3Tool.handle_hero_detail,this)">\
						<span class="hero-portrait"></span>\
						<span class="level">' + data.heroes[i]["level"] + '</span>\
						<span class="name">' + data.heroes[i]["name"] + '</span>\
					</a>\
				</li>';
		}
		html += '</ul>';
		$("#heros").html(html);
	},
	'get_hero_detail' : function(hero_id,callback,active_flag){
		var url = D3Tool.data.settings.url.hero_url(hero_id);
		$(".active").removeClass("active");
		$(active_flag).addClass("active");
		var html = '<tr><thread>\
			<th class="name-column">部位</th>\
			<th>ilvl</th>\
			<th>力量</th>\
			<th>敏捷</th>\
			<th>智力</th>\
			<th>體能</th>\
			<th>生命%</th>\
			<th>擊回</th>\
			<th>吸血%</th>\
			<th>暴率%</th>\
			<th>暴傷%</th>\
			<th>攻速%</th>\
			<th>MF%</th>\
			<th>GF%</th>\
			<th>秒回</th>\
			<th>護甲</th>\
			<th>全抗</th>\
			<th>物抗</th>\
			<th>冰抗</th>\
			<th>火抗</th>\
			<th>電抗</th>\
			<th>毒抗</th>\
			<th>法抗</th>\
			</thread>\
			</tr>';
		$("#items>table").html(html);
		var html = '<tr><thread>\
			<th class="name-column">部位</th>\
			<th>ilvl</th>\
			<th>最小傷害</th>\
			<th>最大傷害</th>\
			<th>額外最小傷害</th>\
			<th>力量</th>\
			<th>敏捷</th>\
			<th>智力</th>\
			<th>體能</th>\
			<th>暴率%</th>\
			<th>暴傷%</th>\
			<th>攻速%</th>\
			</thread>\
			</tr>';
		$("#damage_item>table").html(html);
		D3Tool.get_data(url,callback);
	},
	'handle_hero_detail' : function(data){
		D3Tool.data.cur_hero = data;
		var d = new Date();
		d.setTime(data["last-updated"]*1000);
		$("#last-updated").text("最後更新："+d.getFullYear()+"年"+(d.getMonth()+1)+"月"+d.getDate()+"日 "
			+d.getHours()+"時"+d.getMinutes()+"分"+d.getSeconds()+"秒");
		$("#last-updated").show();
		
		D3Tool.hero_data_fill();
		D3Tool.items.total = 0;
		for(var item in data.items) D3Tool.items.total++;
		D3Tool.items.all_items = {};
		D3Tool.items.curr = 0;
		D3Tool.items.sum_item.attributesRaw = {};
		D3Tool.items.set_item.attributesRaw = {};
		D3Tool.items.damage_item.attributesRaw = {};
		D3Tool.data.sets = {};
		D3Tool.items.item_init(D3Tool.items.sum_item.attributesRaw);
		D3Tool.handle_hero_passive(D3Tool.items.sum_item.attributesRaw,false);
		var item_name = D3Tool.data.settings.item_name;
		for(var i = 0; i < item_name.length;i++)
		{
			if(data.items[item_name[i]] == undefined) continue;
			var url = D3Tool.data.settings.url.item_url(data.items[item_name[i]].tooltipParams);
			D3Tool.get_data(url,D3Tool.handle_item,item_name[i]);
			break;
		}
	},
	'handle_hero_passive' : function(sum_item,recalc){
		//被動技能處理
		data = D3Tool.data.cur_hero;
		if(D3Tool.items.recalc_all_items["mainHand"] == undefined && data.items.mainHand == undefined) return;
		var weapon;
		if(D3Tool.items.recalc_all_items["mainHand"] == undefined) 
			weapon = data.items.mainHand.id;
		else
			weapon = sum_item.id;
			
		var arr = [data["class"],"follower"];
		for(var i = 0;i< arr.length;i++)
		{
			$("#passive-skills-choice-"+arr[i]+" [name='passive-skill']:checked").each(function(){
				var skill = $(this).val();
				var passive_attr = D3Tool.data.settings.hero_primary_source[arr[i]].passive[skill];
				var skill_attr;
				if(typeof passive_attr == 'function')
					skill_attr = D3Tool.data.settings.hero_primary_source[arr[i]].passive[skill](weapon);
				else if(typeof passive_attr == 'object')
					skill_attr = D3Tool.data.settings.hero_primary_source[arr[i]].passive[skill];
				if(skill_attr != undefined){
					for(var attr_name in skill_attr)
					{
						if(sum_item[attr_name] == undefined){
							sum_item[attr_name] = {};
							sum_item[attr_name]["min"] = 0;
						}
						sum_item[attr_name]["min"] += skill_attr[attr_name];
					}
				}
			});
		}
	},
	'items' : {
		'curr' :  0,
		'total' : 0,
		'sum_item' : {'attributesRaw' : {},'itemLevel' : '-','gems' : []},
		'primary_item' :{'attributesRaw' : {},'itemLevel' : '-','gems' : []},
		'set_item' : {'attributesRaw' : {},'itemLevel' : '-','gems' : []},
		'damage_item' : {'attributesRaw' : {},'itemLevel' : '-','gems' : []},
		'recalc_item' : {'attributesRaw' : {},'itemLevel' : '-','gems' : []},
		'names' : [],
		'all_items' : {},
		'recalc_all_items' : {},
		'item_init' : function(item){
			var attrs = ['Strength_Item','Dexterity_Item','Intelligence_Item','Vitality_Item','Hitpoints_Max_Percent_Bonus_Item',
				'Hitpoints_On_Hit','Steal_Health_Percent','Crit_Percent_Bonus_Capped','Crit_Damage_Percent',
				'Attacks_Per_Second_Percent','Magic_Find','Gold_Find','Hitpoints_Regen_Per_Second','Armor_Item',
				'Resistance_All','Resistance#Physical','Resistance#Fire','Resistance#Poision','Resistance#Lightning',
				'Resistance#Arcane','Resistance#Cold'];
			for(var i = 0;i < attrs.length;i++)
			{
				if(item != undefined && item[attrs[i]] == undefined){
					item[attrs[i]] = {};
					item[attrs[i]]["min"] = 0;
				}
			}
		}
	},
	'handle_set_item' : function(data,item){//套裝資料匯總
		if(data.set == undefined) return;
		if(D3Tool.data.sets[data.set.slug] == undefined){
			D3Tool.data.sets[data.set.slug] = {};
			D3Tool.data.sets[data.set.slug]["set_number"] = 1;
			D3Tool.data.sets[data.set.slug]["set_attr"] = [];
		}else{
			D3Tool.data.sets[data.set.slug]["set_number"] ++;
			return;
		}
		for(var i = 0;i< data.set.ranks.length;i++)
		{
			var set_tmp = {};
			set_tmp["required"] = data.set.ranks[i].required;
			set_tmp["attributes"] = data.set.ranks[i].attributes;
			D3Tool.data.sets[data.set.slug]["set_attr"].push(set_tmp);
		}
	},
	'handle_set_attr' : function(){//套裝屬性分析
		D3Tool.items.item_init(D3Tool.items.set_item.attributesRaw);
		for(var set_name in D3Tool.data.sets)
		{//每套
			var set = D3Tool.data.sets[set_name];
			for(var set_attr_name in set.set_attr)
			{//每幾件組成的套裝屬性
				var set_attr = set.set_attr[set_attr_name];
				if(set_attr.required > set.set_number) continue;
				for(var attr_detail_name in set_attr.attributes)
				{//每種套裝屬性
					var attr_detail = set_attr.attributes[attr_detail_name];
					for(var set_attr_sign_name in D3Tool.data.settings.set_item2normal)
					{//每種需要關注的套裝屬性
						if(attr_detail.search(set_attr_sign_name) == -1) continue;
						var value = parseFloat(attr_detail.match(/\d+[.]?\d+/)[0]);
						D3Tool.items.set_item.attributesRaw[D3Tool.data.settings.set_item2normal[set_attr_sign_name]]["min"] += value;
						D3Tool.items.sum_item.attributesRaw[D3Tool.data.settings.set_item2normal[set_attr_sign_name]]["min"] += value;
					}
				}
			}
		}
	},
	'set_damage_max' : function(data,attr){
		var max_attr = attr.replace("Min","Max");
		if(data.attributesRaw[max_attr] == undefined){
			data.attributesRaw[max_attr] = {};
			data.attributesRaw[max_attr]["min"] = 0;
		}
		if(attr == "Damage_Weapon_Min#Physical" && data.minDamage != undefined){
			data.attributesRaw[attr].min = data.minDamage.min;
			data.attributesRaw[max_attr].min = data.maxDamage.min;
		}else{
			data.attributesRaw[max_attr].min = data.attributesRaw[attr].min + data.attributesRaw[attr.replace("Min","Delta")].min;
		}
	},
	'handle_damage_item' : function(data,item){
		for(var attr_name in data.attributesRaw)
		{
			if(attr_name.indexOf("Damage_Weapon_Min") > -1) D3Tool.set_damage_max(data,attr_name);
		}
		for(var attr_name in data.attributesRaw)
		{
			var attr = data.attributesRaw[attr_name];
			var arr = attr_name.split("#");
			var real_attr_name = arr[0];
			if(data.attributesRaw[real_attr_name] == undefined){
				data.attributesRaw[real_attr_name] = {};
				data.attributesRaw[real_attr_name]["min"] = 0;
			}
			if(D3Tool.items.damage_item.attributesRaw[real_attr_name] == undefined){
				D3Tool.items.damage_item.attributesRaw[real_attr_name] = {};
				D3Tool.items.damage_item.attributesRaw[real_attr_name]["min"] = 0;
			}
			if(D3Tool.data.settings.item_percent[real_attr_name] != undefined){
				attr.min *= 100;
			}
			D3Tool.items.damage_item.attributesRaw[real_attr_name]["min"] += attr.min;
			if(attr_name != real_attr_name) data.attributesRaw[real_attr_name]["min"] += attr.min;
		}
		D3Tool.handle_gem_item(data,D3Tool.items.damage_item.attributesRaw);
		D3Tool.build_item_html(data,item,D3Tool.data.settings.item_damage_attr,"damage_item");
	},
	'handle_gem_item' : function(data,sum_item){
		for(var i = 0;i < data.gems.length;i++)
		{//裝備寶石屬性匯總
			for(var attr_name in data.gems[i].attributesRaw)
			{
				if(sum_item[attr_name] == undefined){
					sum_item[attr_name] = {};
					sum_item[attr_name]["min"] = 0;
				}
				if(data.gems[i].attributesRaw != undefined && data.gems[i].attributesRaw[attr_name] != undefined){
					if(D3Tool.data.settings.item_percent[attr_name] != undefined 
						&& data.gems[i].attributesRaw[attr_name].min == data.gems[i].attributesRaw[attr_name].max){
						data.gems[i].attributesRaw[attr_name].min *= 100;
					}
					sum_item[attr_name]["min"] += item_min(data.gems[i].attributesRaw[attr_name]);
				}
			}
		}
	},
	'pre_handle_item' : function(data,item){
		if(data.attributesRaw.Armor_Bonus_Item != undefined){
			//護甲增加屬性特殊處理，增加在基礎護甲屬性上
			if(data.attributesRaw.Armor_Item != undefined){
				data.attributesRaw.Armor_Item.min += data.attributesRaw.Armor_Bonus_Item.min;
			}else{
				data.attributesRaw["Armor_Item"] = {};
				data.attributesRaw["Armor_Item"]["min"] = data.attributesRaw.Armor_Bonus_Item.min;
			}
		}
		if(data.attributesRaw.Armor_Item != undefined){
			//護甲值不算小數
			data.attributesRaw.Armor_Item.min = parseInt(data.attributesRaw.Armor_Item.min);
		}
		if(data.attributesRaw["Damage_Min#Physical"] != undefined){
			data.attributesRaw["Damage_Weapon_Min#Physical"] = data.attributesRaw["Damage_Min#Physical"];
		}
		if(data.attributesRaw["Damage_Delta#Physical"] != undefined){
			data.attributesRaw["Damage_Weapon_Delta#Physical"] = data.attributesRaw["Damage_Delta#Physical"];
		}
		if(data.attributesRaw["Attacks_Per_Second_Item_Percent"] != undefined){
			data.attributesRaw["Attacks_Per_Second_Percent"] = data.attributesRaw["Attacks_Per_Second_Item_Percent"];
		}
	},
	'handle_item' : function(data,item){
		D3Tool.items.curr ++;
		D3Tool.pre_handle_item(data,item);//預處理
		D3Tool.handle_set_item(data,item);//處理套裝
		if(D3Tool.data.settings.damage_item[item] != undefined)
			D3Tool.handle_damage_item(data,item);//處理帶傷害裝備
		for(var attr in data.attributesRaw)
		{//裝備基礎屬性匯總
			if(D3Tool.items.sum_item.attributesRaw[attr] == undefined){
				D3Tool.items.sum_item.attributesRaw[attr] = {};
				D3Tool.items.sum_item.attributesRaw[attr]["min"] = 0;
			}
			if(D3Tool.data.settings.item_percent[attr] != undefined 
				&& data.attributesRaw[attr].min == data.attributesRaw[attr].max){
				data.attributesRaw[attr].min *= 100;
			}
			D3Tool.items.sum_item.attributesRaw[attr]["min"] +=  item_min(data.attributesRaw[attr]);
		}
		//裝備寶石屬性匯總
		D3Tool.handle_gem_item(data,D3Tool.items.sum_item.attributesRaw);
		
		D3Tool.build_item_html(data,item,D3Tool.data.settings.item_normal_attr,'items');
		
		D3Tool.items.all_items[item] = data;
		if(D3Tool.items.curr == D3Tool.items.total){
			//基礎屬性計算
			D3Tool.items.curr = 0;
			var level = D3Tool.data.cur_hero.level + D3Tool.data.cur_hero.paragonLevel;
			var attrs=['Strength_Item','Dexterity_Item','Intelligence_Item','Vitality_Item'];
			D3Tool.items.item_init(D3Tool.items.primary_item.attributesRaw);
			for(var i = 0;i < attrs.length;i++)
			{
				D3Tool.items.primary_item.attributesRaw[attrs[i]]["min"] = (7 + primary_add(attrs[i]) * level)
				D3Tool.items.sum_item.attributesRaw[attrs[i]]["min"] += D3Tool.items.primary_item.attributesRaw[attrs[i]]["min"];
			}
			D3Tool.items.sum_item.attributesRaw['Crit_Percent_Bonus_Capped']["min"] += D3Tool.items.primary_item.attributesRaw['Crit_Percent_Bonus_Capped']["min"] = 5;
			D3Tool.items.sum_item.attributesRaw['Crit_Damage_Percent']["min"] += D3Tool.items.primary_item.attributesRaw['Crit_Damage_Percent']["min"] = 50;
			
			D3Tool.handle_set_attr();
			
			D3Tool.items.sum_item.attributesRaw['Armor_Item']["min"] += D3Tool.items.sum_item.attributesRaw['Strength_Item']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"] += D3Tool.items.sum_item.attributesRaw['Intelligence_Item']["min"]/10;
			D3Tool.items.sum_item.attributesRaw['Resistance#Physical']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance#Fire']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance#Poision']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance#Lightning']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance#Arcane']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			D3Tool.items.sum_item.attributesRaw['Resistance#Cold']["min"] += D3Tool.items.sum_item.attributesRaw['Resistance_All']["min"];
			
			D3Tool.build_item_html(D3Tool.items.damage_item,"匯總",D3Tool.data.settings.item_damage_attr,"damage_item");
			D3Tool.build_item_html(D3Tool.items.primary_item,'基礎屬性',D3Tool.data.settings.item_normal_attr,'items');
			D3Tool.build_item_html(D3Tool.items.set_item,'套裝屬性',D3Tool.data.settings.item_normal_attr,'items');
			D3Tool.build_item_html(D3Tool.items.sum_item,'匯總',D3Tool.data.settings.item_normal_attr,'items');
			D3Tool.fill_sum_item();
		}else if(D3Tool.items.curr < D3Tool.items.total){
			hero_data = D3Tool.data.cur_hero;
			var item_name = D3Tool.data.settings.item_name;
			if(array_index(item_name,item) == -1){
				alert("獲取裝備屬性錯誤！");
				return;
			}
			for(var i = array_index(item_name,item) + 1; i < item_name.length;i++)
			{
				if(hero_data.items[item_name[i]] == undefined) continue;
				var url = D3Tool.data.settings.url.item_url(hero_data.items[item_name[i]].tooltipParams);
				D3Tool.get_data(url,D3Tool.handle_item,item_name[i]);
				break;
			}
		}
	},
	'fill_sum_item' : function(){
		//填充匯總後計算出的資料
		var sum_item = D3Tool.items.sum_item.attributesRaw;
		$("#attr-strength-hero>.value").text(item_min(sum_item["Strength_Item"]));
		$("#attr-dexterity-hero>.value").text(item_min(sum_item["Dexterity_Item"]));
		$("#attr-intelligence-hero>.value").text(item_min(sum_item["Intelligence_Item"]));
		$("#attr-vitality-hero>.value").text(item_min(sum_item["Vitality_Item"]));
		$("#attr-armor-hero>.value").text(item_min(sum_item["Armor_Item"]));
		
		D3Tool.items.sum_item.attributesRaw["dps"] = digit_2(D3Tool.get_dps(D3Tool.items.sum_item.attributesRaw,D3Tool.items.all_items));
		$("#attr-dps-hero>.value").text(D3Tool.items.sum_item.attributesRaw["dps"]);
		
		
		$("#attr-critChance-hero>.value").text(item_min(sum_item["Crit_Percent_Bonus_Capped"])+"%");
		$("#attr-critDamage-hero>.value").text(item_min(sum_item["Crit_Damage_Percent"])+"%");
		
		$("#attr-physical-resist>.value").text(item_min(sum_item["Resistance#Physical"]));//物抗
		$("#attr-fire-resist>.value").text(item_min(sum_item["Resistance#Fire"]));//火抗
		$("#attr-cold-resist>.value").text(item_min(sum_item["Resistance#Cold"]));//冰抗
		$("#attr-lightning-resist>.value").text(item_min(sum_item["Resistance#Lightning"]));//電抗
		$("#attr-poison-resist>.value").text(item_min(sum_item["Resistance#Poision"]));//毒抗
		$("#attr-arcane-resist>.value").text(item_min(sum_item["Resistance#Arcane"]));//法抗
		
		$("#attr-lifeSteal-hero>.value").text(item_min(sum_item["Steal_Health_Percent"])+"%");//生命偷取
		$("#attr-lifeOnHit-hero>.value").text(item_min(sum_item["Hitpoints_On_Hit"]));//擊中回復
	},
	'get_dps' : function(sum_item,items){
		var two_weapon = false;
		var main_hand ,off_hand;
		if((main_hand = items["mainHand"]) == undefined) main_hand = D3Tool.items.all_items["mainHand"];
		if((off_hand = items["offHand"]) == undefined) off_hand = D3Tool.items.all_items["offHand"];
		if(off_hand != undefined && off_hand.dps != undefined){
			two_weapon = true;
		}
		var pri_attr = item_min(sum_item[D3Tool.data.settings.hero_primary_source[D3Tool.data.cur_hero["class"]].main_primary]) / 100;
		var crit_add = (item_min(sum_item.Crit_Percent_Bonus_Capped) + item_min(sum_item.Crit_Percent_Bonus_Capped_Skill)) /100 
			* (item_min(sum_item.Crit_Damage_Percent) + item_min(sum_item.Crit_Damage_Percent_Skill)) / 100;
		var passive_add = item_min(sum_item.Passive_Skill_Percent_Bonus) / 100;
		var dph_all = item_min(sum_item.Damage_Weapon_Min) + item_min(sum_item.Damage_Weapon_Max) + item_min(sum_item.Damage_Bonus_Min);
		var weapon_speed1 = 0,weapon_base_speed1 = 0,weapon_speed_bonus1 = 0,other_speed = 0;
		var weapon_speed2 = 0,weapon_base_speed2 = 0,weapon_speed_bonus2 = 0;
		var weapon_dph1 = 0,weapon_dph2 = 0 ,dps = 0;
		var const_a = (1 + pri_attr) * (1 + crit_add) * (1 + passive_add);
		if(two_weapon){
			//主手dph
			weapon_dph1 = (dph_all - item_min(off_hand.attributesRaw.Damage_Weapon_Min) - item_min(off_hand.attributesRaw.Damage_Weapon_Max)) / 2;
			//副手dph
			weapon_dph2 = (dph_all - item_min(main_hand.attributesRaw.Damage_Weapon_Min) - item_min(main_hand.attributesRaw.Damage_Weapon_Max)) / 2;
			//主手基礎攻速
			weapon_base_speed1 = item_min(main_hand.attributesRaw.Attacks_Per_Second_Item) + item_min(main_hand.attributesRaw.Attacks_Per_Second_Item_Bonus);
			//副手基礎攻速
			weapon_base_speed2 = item_min(off_hand.attributesRaw.Attacks_Per_Second_Item) + item_min(main_hand.attributesRaw.Attacks_Per_Second_Item_Bonus);
			//主手藍字攻速
			weapon_speed_bonus1 = item_min(main_hand.attributesRaw.Attacks_Per_Second_Percent) / 100;
			//副手藍字攻速
			weapon_speed_bonus2 = item_min(off_hand.attributesRaw.Attacks_Per_Second_Percent) / 100;
			//主手攻速
			weapon_speed1 = weapon_base_speed1 * (1 + weapon_speed_bonus1);
			//副手攻速
			weapon_speed2 = weapon_base_speed2 * (1 + weapon_speed_bonus2);
			//其他部位攻速
			other_speed = item_min(sum_item.Attacks_Per_Second_Percent) / 100 - weapon_speed_bonus1 - weapon_speed_bonus2;
			dps = [(weapon_dph1 + weapon_dph2)*weapon_speed1*weapon_speed2/(weapon_speed1 + weapon_speed2)] * (1 + 0.15 + other_speed) * const_a;
		}else{
			weapon_dph1 = dph_all / 2;
			weapon_base_speed1 = item_min(main_hand.attributesRaw.Attacks_Per_Second_Item) + item_min(main_hand.attributesRaw.Attacks_Per_Second_Item_Bonus);
			weapon_speed_bonus1 = item_min(main_hand.attributesRaw.Attacks_Per_Second_Percent) / 100;
			other_speed = item_min(sum_item.Attacks_Per_Second_Percent) / 100 - weapon_speed_bonus1;
			dps = weapon_dph1 * weapon_base_speed1 * (1 + weapon_speed_bonus1) * (1 + other_speed) * const_a;
		}
		return dps;
	},
	're_calc_dps' : function(){
		var recalc_all_items = D3Tool.items.recalc_all_items;
		var sum_item = D3Tool.items.sum_item.attributesRaw;
		recalc_all_items = {};
		D3Tool.items.recalc_item.attributesRaw = $.extend(true,{},sum_item);
		var recalc_item = D3Tool.items.recalc_item.attributesRaw;
		$("[name=add_item_choosed]:checked").each(function(){
			var item_name = $(this).val();
			var weapon_name = $(this).parent().next().children("select").val();
			var weapon = D3Tool.data.settings.weapons[weapon_name];
			if(item_name == "mainHand") recalc_item["id"] = weapon_name;
			
			if(recalc_all_items[item_name] == undefined){
				recalc_all_items[item_name] = {};
				recalc_all_items[item_name]["attributesRaw"] = {};
			}
			if(item_name == "offHand" && weapon_name != "normal") recalc_all_items[item_name]["dps"] = {};
			
			if(item_name == "mainHand" || (item_name == "offHand" && weapon_name != "normal")){
				recalc_all_items[item_name].attributesRaw["Attacks_Per_Second_Item"] = {};
				recalc_all_items[item_name].attributesRaw["Attacks_Per_Second_Item"]["min"] = weapon.speed;
			}
			$(this).parent().parent().find(":text").each(function(){
				var attr_name = $(this).attr("id");
				var value = parseFloat($(this).val());
				if(isNaN(value)) value = 0;
				
				if(recalc_all_items[item_name].attributesRaw[attr_name] == undefined){
					recalc_all_items[item_name].attributesRaw[attr_name] = {};
					recalc_all_items[item_name].attributesRaw[attr_name]["min"] = value;
				}
				if(recalc_item[attr_name] == undefined){
					recalc_item[attr_name] = {};
					recalc_item[attr_name]["min"] = value;
				}else{
					var gem_item = {};
					D3Tool.handle_gem_item(D3Tool.items.all_items[item_name],gem_item);
					recalc_item[attr_name]["min"] = item_min(recalc_item[attr_name]) - item_min(D3Tool.items.all_items[item_name].attributesRaw[attr_name]) 
						- item_min(gem_item[attr_name]) + value;
					if(item_name == "mainHand" && weapon.type == 2){
						//雙手武器禁掉副手
						off_hand = "offHand";
						if(recalc_all_items[off_hand] == undefined){
							recalc_all_items[off_hand] = {};
							recalc_all_items[off_hand]["attributesRaw"] = {};
						}
						if(recalc_all_items[off_hand].attributesRaw[attr_name] == undefined){
							recalc_all_items[off_hand].attributesRaw[attr_name] = {};
							recalc_all_items[off_hand].attributesRaw[attr_name]["min"] = 0;
						}
						var gem_item2 = {};
						D3Tool.handle_gem_item(D3Tool.items.all_items[off_hand],gem_item2);
						recalc_item[attr_name]["min"] = item_min(recalc_item[attr_name]) - item_min(D3Tool.items.all_items[off_hand].attributesRaw[attr_name]) 
							- item_min(gem_item2[attr_name]);
					}
				}
			});
		});
		recalc_item.Crit_Percent_Bonus_Capped_Skill = undefined;
		recalc_item.Crit_Damage_Percent_Skill = undefined;
		recalc_item.Passive_Skill_Percent_Bonus = undefined;
		D3Tool.handle_hero_passive(recalc_item);
		var dps = D3Tool.get_dps(recalc_item,recalc_all_items);
		recalc_item["dps"] = dps;
		$("#attr-old-dps-hero>.value").text(digit_2(sum_item.dps));
		$("#attr-new-dps-hero>.value").text(digit_2(dps));
		var diff_dps = digit_2(dps) - digit_2(sum_item.dps);
		$("#attr-diff-dps-hero>.value").text(digit_2(diff_dps));
		if(diff_dps >= 0 ){
			$("#attr-diff-dps-hero>.value").css("color","green");
		}else{
			$("#attr-diff-dps-hero>.value").css("color","red");
		}
		
	},
	'build_item_html' : function(data,item,show_arr,show_div_name){
		var html = '';
		if(D3Tool.items.curr % 2 == 0) tr_class = 'class="row2"'; else tr_class = '';
		if(item == '匯總') tr_class = 'class="sum"';
		html += '<tr ' + tr_class + ' item_name="'+ item +'" ondblclick="D3Tool.handle_item_change(this,\''+show_div_name+'\')" align="center">' ;
		html += '<td>' + D3Tool.data.settings.item_chinese[item] + '</td>';
		html += '<td>' + data.itemLevel + '</td>';
		for(var i = 0;i< show_arr.length;i++)
		{
			html += D3Tool.item_analyze(data,show_arr[i]);
		}
		html +=	'</tr>';
		$("#" + show_div_name + ">table").append(html);
	},
	'item_analyze' : function(item,attr){
		var html = '<td attr_name="'+ attr +'">';
		var val = 0;
		html += item_min(item.attributesRaw[attr]);
		for(var i = 0;i < item.gems.length;i++)
		{
			if(item.gems[i].attributesRaw != undefined && item.gems[i].attributesRaw[attr] != undefined){
				val += item_min(item.gems[i].attributesRaw[attr]);
			}
		}
		if(val > 0) html += '<font color="' + D3Tool.data.settings.gem_color[attr] + '">+' + val + '</font>';
		html += '</td>';
		return html;
	},
	'damage_item_td_html' : '<td><input type="text" size=3 id="Damage_Weapon_Min"></td>\
		<td><input type="text" size=3 id="Damage_Weapon_Max"></td>\
		<td><input type="text" size=3 id="Damage_Bonus_Min"></td>\
		<td><input type="text" size=3 id="Strength_Item"></td>\
		<td><input type="text" size=3 id="Dexterity_Item"></td>\
		<td><input type="text" size=3 id="Intelligence_Item"></td>\
		<td><input type="text" size=3 id="Vitality_Item"></td>\
		<td><input type="text" size=3 id="Crit_Percent_Bonus_Capped"></td>\
		<td><input type="text" size=3 id="Crit_Damage_Percent"></td>\
		<td><input type="text" size=3 id="Attacks_Per_Second_Percent"></td>\
	',
	'normal_item_td_html' : '<td><input type="text" size=3 id="Strength_Item"></td>\
		<td><input type="text" size=3 id="Dexterity_Item"></td>\
		<td><input type="text" size=3 id="Intelligence_Item"></td>\
		<td><input type="text" size=3 id="Vitality_Item"></td>\
		<td><input type="text" size=3 id="Hitpoints_Max_Percent_Bonus_Item"></td>\
		<td><input type="text" size=3 id="Hitpoints_On_Hit"></td>\
		<td><input type="text" size=3 id="Steal_Health_Percent"></td>\
		<td><input type="text" size=3 id="Crit_Percent_Bonus_Capped"></td>\
		<td><input type="text" size=3 id="Crit_Damage_Percent"></td>\
		<td><input type="text" size=3 id="Attacks_Per_Second_Percent"></td>\
		<td><input type="text" size=3 id="Magic_Find"></td>\
		<td><input type="text" size=3 id="Gold_Find"></td>\
		<td><input type="text" size=3 id="Hitpoints_Regen_Per_Second"></td>\
		<td><input type="text" size=3 id="Armor_Item"></td>\
		<td><input type="text" size=3 id="Resistance_All"></td>\
		<td><input type="text" size=3 id="Resistance#Physical"></td>\
		<td><input type="text" size=3 id="Resistance#Cold"></td>\
		<td><input type="text" size=3 id="Resistance#Fire"></td>\
		<td><input type="text" size=3 id="Resistance#Lightning"></td>\
		<td><input type="text" size=3 id="Resistance#Poision"></td>\
		<td><input type="text" size=3 id="Resistance#Arcane"></td>\
	',
	'weapon_option' : function(weapon_type){
		var html = '<select id="add_weapon_select">';
		if(weapon_type == 'offHand'){
			html += '<option value ="normal">普通副手</option>'
		}
		for(var weapon_name in D3Tool.data.settings.weapons)
		{
			html += '<option value ="' + weapon_name + '">'+ D3Tool.data.settings.weapons[weapon_name].ch +'</option>'
		}
		html += '</select>';
		return html;
	},
	'handle_item_change' : function(node,show_div_name){
		var item_name = $(node).attr("item_name");
		if(item_name == "匯總" ||item_name == "基礎屬性" ||item_name == "套裝屬性")
			return;
		if(D3Tool.data.settings.damage_item[item_name] != undefined && show_div_name != "damage_item"){
			alert("傷害屬性裝備請在上面表格填寫！");
			return;
		}
		var html = '<tr id=' + item_name + '>';
		html += '<td><input type="checkbox" class="add_item_'+item_name+'" name="add_item_choosed" value="'+item_name+'"></td>';
		html += '<td>';
		if(D3Tool.get_add_item_td[item_name] != undefined)
			html += D3Tool.get_add_item_td[item_name]();
		html += '</td>';
		if(D3Tool.data.settings.damage_item[item_name] != undefined && show_div_name == "damage_item"){
			html += D3Tool.damage_item_td_html;
		}else{
			html += D3Tool.normal_item_td_html;
		}
		html += '</tr>';
		$(node).after(html).next().find(":checkbox").click(function(){
			if($(this).attr("checked") == "checked"){
				$(".add_item_"+item_name).not(this).removeAttr("checked","")
			}
		});
	},
	'get_add_item_td' : {
		'mainHand': function(){return D3Tool.weapon_option('mainHand')},
		'offHand': function(){return D3Tool.weapon_option('offHand')}
	},
	'hero_data_fill' : function(){
		data = D3Tool.data.cur_hero;
		var url = D3Tool.data.settings.url;
		for(var i = 0;i < data.skills.active.length ;i++)
		{//主動技能
			if(data.skills.active[i].skill == undefined){//技能為空
				$("#active-skill-tooltip-" + i + "-link>.skill-name>.skill").text("");
				$("#active-skill-tooltip-" + i + "-link>.skill-name>.rune-name").text("");
				$("#active-skill-tooltip-" + i + "-link>.d3-icon").css('background-image',"");
				$("#active-skill-tooltip-" + i + "-link").attr("tip-url","");
				$("#active-skill-tooltip-" + i + "-link").attr("tip-slug","");
				var skill_class = 'unlocked';
				if(i > 0) {//判斷技能是否鎖定
					if(data.level < D3Tool.data.settings.skill_unlock_level['slot'+i])
						skill_class = "locked-" +  D3Tool.data.settings.skill_unlock_level['slot'+i];
				}
				$("#active-skill-tooltip-" + i + "-link>.d3-icon").html("<span class=\"empty-skill " + skill_class + "\"></span>");
				continue;
			}
			$("#active-skill-tooltip-" + i + "-link>.d3-icon").html("<span class=\"frame\"></span>");
			$("#active-skill-tooltip-" + i + "-link>.d3-icon").css('background-image',"url("+url.skill_icon_url(data.skills.active[i].skill.icon)+")");
			$("#active-skill-tooltip-" + i + "-link>.skill-name>.skill").text(data.skills.active[i].skill.name);
			if(data.skills.active[i].rune != undefined){
				$("#active-skill-tooltip-" + i + "-link>.skill-name>.rune-name").text(data.skills.active[i].rune.name);
				$("#active-skill-tooltip-" + i + "-link").attr("tip-url",url.skill_url(data.skills.active[i].skill.tooltipUrl)+"|"+
					url.skill_url(data.skills.active[i].rune.tooltipParams));
				$("#active-skill-tooltip-" + i + "-link").attr("tip-slug",data.skills.active[i].skill.slug+"|"+
					data.skills.active[i].rune.slug);
			}else{
				$("#active-skill-tooltip-" + i + "-link>.skill-name>.rune-name").text("");
				$("#active-skill-tooltip-" + i + "-link").attr("tip-url",url.skill_url(data.skills.active[i].skill.tooltipUrl));
				$("#active-skill-tooltip-" + i + "-link").attr("tip-slug",data.skills.active[i].skill.slug);
			}
		}
		for(var i = 0;i < data.skills.passive.length ;i++)
		{//被動技能
			$("#passive-skill-tooltip-" + i + "-link>.passive-icon").empty();
			var skill_class = '';
			if(data.level < D3Tool.data.settings.skill_unlock_level['passive_slot'+i])
				skill_class = "locked-passive-" +  D3Tool.data.settings.skill_unlock_level['passive_slot'+i];
			if(data.skills.passive[i].skill == undefined){//技能為空
				$("#passive-skill-tooltip-" + i + "-link>.passive-icon").addClass("empty-passive tip " + skill_class);
				$("#passive-skill-tooltip-" + i + "-link>.skill-name").text("");
				$("#passive-skill-tooltip-" + i + "-link").attr("tip-url","");
				$("#passive-skill-tooltip-" + i + "-link").attr("tip-slug","");
				continue;
			}
			$("#passive-skill-tooltip-" + i + "-link>.passive-icon").removeClass("empty-passive tip " + skill_class);
			$("#passive-skill-tooltip-" + i + "-link>.passive-icon").append('<img src="' + url.skill_icon_url(data.skills.passive[i].skill.icon) + '" style="width:32px; height:32px">');
			$("#passive-skill-tooltip-" + i + "-link>.skill-name").text(data.skills.passive[i].skill.name);
			$("#passive-skill-tooltip-" + i + "-link").attr("tip-url",url.skill_url(data.skills.passive[i].skill.tooltipUrl));
			$("#passive-skill-tooltip-" + i + "-link").attr("tip-slug",data.skills.passive[i].skill.slug);
			
			//被動技能複選
			$(".passive-skills-choice").hide();
			$("#passive-skills-choice-"+data["class"]).show();
			$("#passive-skills-choice-follower").show();
			if(D3Tool.data.settings.hero_primary_source[data["class"]].passive[data.skills.passive[i].skill.slug] != undefined){
				$("#passive-skills-choice-"+data["class"]+" [value='"+data.skills.passive[i].skill.slug+"']").attr("checked","true");
			}
		}
		$("[name=passive-skill]").each(function(){
			var skill_name = $(this).val();
			var owner = $(this).attr("owner");
			if(owner == undefined){
				owner = data["class"];
			}
			$(this).parent().attr("tip-url",url.skill_url("skill/"+owner+"/"+skill_name));
			$(this).parent().attr("tip-slug",skill_name);
		});
		$("#attr-attackSpeed-hero>.value").text(digit_2(data.stats.attackSpeed));
		
	$("#attr-primary-resource>.resource-icon").removeClass().addClass("resource-icon resource-"+D3Tool.data.settings.hero_primary_source[data["class"]]["class"]);
		if(data["class"] == 'demon-hunter'){
			$("#attr-primary-resource>.resource-icon>.value").text(digit_2(data.stats.primaryResource)+"-"+digit_2(data.stats.secondaryResource));
		}else{
			$("#attr-primary-resource>.resource-icon>.value").text(digit_2(data.stats.primaryResource));
		}//能量值
		$("#attr-primary-resource>.label-wrapper>.label").text(D3Tool.data.settings.hero_primary_source[data["class"]].ch);//能量名稱
		$("#attr-life-hero>.resource-icon>.value").text(digit_2(data.stats.life/1000)+"K");//生命
		
		
		$("#attr-goldFind-hero>.value").text(digit_2(data.stats.goldFind*100) +"%");//GF
		$("#attr-magicFind-hero>.value").text(digit_2(data.stats.magicFind*100) +"%");//MF
		
	}
};
function item_min(d){
	return d == undefined?0:parseInt(d.min*100+0.5)/100;
}
function digit_2(d){
	return parseInt(d*100+0.5)/100;
}
function array_index(arr,x){
	if(arr == undefined) return -1;
	for(var i = 0 ;i< arr.length; i++)
	{
		if(arr[i] == x) return i;
	}
	return -1;
}
function primary_add(attr_name)
{
	if(attr_name == 'Vitality_Item') return 2;
	if(attr_name == D3Tool.data.settings.hero_primary_source[D3Tool.data.cur_hero["class"]].main_primary) return 3;
	return 1;
}
