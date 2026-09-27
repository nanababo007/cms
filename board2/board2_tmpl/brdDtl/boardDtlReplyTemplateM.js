var replyItemTemplateString = `
	<div class="mb-3 border-bottom pb-2 reply-item-class"  id="replyListItemArea{{bdrSeq}}" data-bdr_seq="{{bdrSeq}}">
		<div>
			<a href="javascript:modifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');">수정</a> | 
			<a href="javascript:deleteReply('{{bdrSeq}}','{{bdrSeqId}}');" style="color:red;">삭제</a> | 
			<a href="javascript:fixReply('{{bdrSeq}}','{{bdrFixYN}}','{{bdrSeqId}}');">고정</a>
		</div>
		<div id="replyItemEdit{{bdrSeqId}}" style="display:none;">
			<textarea class="form-control" id="replyItemEditText{{bdrSeqId}}" rows="4" placeholder="댓글내용을 입력해주세요">{{orgReplyContent}}</textarea>
			<div>
				<button class="btn btn-light border flex-shrink-0" onclick="modifyReply('{{bdrSeq}}','{{bdrSeqId}}');">댓글수정</button>
				<button class="btn btn-light border flex-shrink-0" onclick="cancelModifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');">댓글수정 취소</button>
			</div>
		</div>
		<div class="fw-bold">{{replyDatetime}}</div>
		<!--<div class="text-muted mb-1">5분전</div>-->
		<div class="reply-item-view-class" id="replyItemView{{bdrSeqId}}">{{replyContent}}</div>
		<!--2차댓글-->
		<div id="reply2ListItemOutArea{{bdrSeqId}}" style="margin-top:10px;padding-left:20px;" class="reply2-area-class">
			<div class="mb-3  pb-2 reply2-item-class"  id="reply2ListItemArea{{bdr2Seq}}" data-bdr_seq="{{bdrSeq}}">
				<h4 style="margin:0;padding:0;font-size: 1em;font-weight:bold;margin-bottom:8px;">하위댓글 ({{reply2Cnt}}개)</h4>
				<div id="reply2ItemEdit{{bdrSeqId}}">
					<textarea class="form-control" id="reply2Content{{bdrSeqId}}" rows="4" placeholder="댓글내용을 입력해주세요"></textarea>
					<div style="margin-top:8px;">
						<button class="btn btn-light border flex-shrink-0" onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].writeReply2();">하위댓글등록</button>
						<button class="btn btn-light border flex-shrink-0" onclick="javascript:g_reply2ObjectList['{{bdrSeqId}}'].cancelReply2();">하위댓글취소</button>
					</div>
				</div>
				<div class="reply2-list-area-class" style="margin-top:8px;border:0;"></div>
			</div>
		</div><!-- //reply2ListItemOutArea -->
		<!--//2차댓글-->
	</div>
`;