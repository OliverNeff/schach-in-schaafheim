--
-- 3.3.5 neue Berechtigung f�r PGN Import
--
REPLACE INTO `#__clm_usertype` (`id`, `name`, `usertype`, `kind`, `published`, `ordering`, `params`) VALUES
(1, 'Administrator', 'admin', 'CLM', 1, 0, 'BE_general_general=1\nBE_season_general=1\nBE_event_general=1\nBE_event_delete=1\nBE_tournament_general=1\nBE_tournament_create=1\nBE_tournament_edit_detail=1\nBE_tournament_delete=1\nBE_tournament_edit_round=1\nBE_tournament_edit_result=1\nBE_tournament_edit_fixture=1\nBE_league_general=1\nBE_league_create=1\nBE_league_edit_detail=1\nBE_league_delete=1\nBE_league_edit_round=1\nBE_league_edit_result=1\nBE_league_edit_fixture=1\nBE_teamtournament_general=1\nBE_teamtournament_create=1\nBE_teamtournament_edit_detail=1\nBE_teamtournament_delete=1\nBE_teamtournament_edit_round=1\nBE_teamtournament_edit_result=1\nBE_teamtournament_edit_fixture=1\nBE_club_general=1\nBE_club_create=1\nBE_club_edit_member=1\nBE_club_copy=1\nBE_club_edit_ranking=1\nBE_team_general=1\nBE_team_create=1\nBE_team_edit=1\nBE_team_delete=1\nBE_team_registration_list=1\nBE_user_general=1\nBE_user_copy=1\nBE_accessgroup_general=1\nBE_swt_general=1\nBE_pgn_general=1\nBE_dewis_general=1\nBE_database_general=1\nBE_logfile_general=1\nBE_logfile_delete=1\nBE_config_general=1'),
(4, 'DWZ Referent', 'dwz', 'CLM', 1, 4, 'BE_general_general=1\nBE_event_general=1\nBE_tournament_general=1\nBE_tournament_create=1\nBE_tournament_edit_detail=1\nBE_tournament_delete=1\nBE_tournament_edit_round=1\nBE_tournament_edit_result=1\nBE_tournament_edit_fixture=1\nBE_league_general=1\nBE_league_create=1\nBE_league_edit_detail=1\nBE_league_delete=1\nBE_league_edit_round=1\nBE_league_edit_result=1\nBE_league_edit_fixture=1\nBE_teamtournament_general=1\nBE_teamtournament_create=1\nBE_teamtournament_edit_detail=1\nBE_teamtournament_delete=1\nBE_teamtournament_edit_round=2\nBE_teamtournament_edit_result=2\nBE_teamtournament_edit_fixture=2\nBE_club_general=1\nBE_club_create=1\nBE_club_edit_member=1\nBE_team_general=1\nBE_team_create=1\nBE_team_registration_list=1\nBE_swt_general=1\nBE_pgn_general=1\nBE_dewis_general=1\nBE_database_general=1\nBE_logfile_general=1');

